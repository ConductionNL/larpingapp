<?php
declare(strict_types=1);
namespace OCA\LarpingApp\Tests\Unit\Service;
use OCA\LarpingApp\Service\RegisterObjectFetcher;
use OCP\App\IAppManager;
use OCP\IAppConfig;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class RegisterObjectFetcherTest extends TestCase
{
    private ContainerInterface&MockObject $container;
    private IAppManager&MockObject $appManager;
    private IAppConfig&MockObject $config;
    private RegisterObjectFetcher $fetcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = $this->createMock(ContainerInterface::class);
        $this->appManager = $this->createMock(IAppManager::class);
        $this->config = $this->createMock(IAppConfig::class);
        $this->fetcher = new RegisterObjectFetcher($this->container, $this->appManager, $this->config);
    }

    private function setupOpenRegister(object $mapperMock): void
    {
        $this->appManager->method('getInstalledApps')->willReturn(['openregister']);
        $openRegMock = new class($mapperMock) {
            private object $m;
            public function __construct(object $m) { $this->m = $m; }
            public function getMapper(string $r, string $s): object { return $this->m; }
        };
        $this->container->method('get')->willReturn($openRegMock);
    }

    private function configureType(string $type, string $register = 'reg-1', string $schema = 'sch-1'): void
    {
        $this->config->method('getValueString')->willReturnCallback(
            function (string $app, string $key, string $default) use ($type, $register, $schema) {
                if ($key === $type . '_register') return $register;
                if ($key === $type . '_schema') return $schema;
                return $default;
            }
        );
    }

    public function testGetObjectsCallsMapperFindAll(): void
    {
        $mapperMock = new class {
            public function findAll($l=null,$o=null,$f=[],$s=[],$sr=null): array {
                return [['id' => 'sk1', 'name' => 'Sword']];
            }
        };
        $this->setupOpenRegister($mapperMock);
        $this->configureType('skill');
        $result = $this->fetcher->getObjects('skill');
        self::assertCount(1, $result);
        self::assertSame('Sword', $result[0]['name']);
    }

    public function testGetObjectsCaseInsensitive(): void
    {
        $mapperMock = new class { public function findAll($l=null,$o=null,$f=[],$s=[],$sr=null): array { return []; } };
        $this->setupOpenRegister($mapperMock);
        $this->configureType('skill');
        $result = $this->fetcher->getObjects('Skill');
        self::assertIsArray($result);
    }

    public function testThrowsWhenRegisterNotConfigured(): void
    {
        $this->appManager->method('getInstalledApps')->willReturn(['openregister']);
        $this->container->method('get')->willReturn(new \stdClass());
        $this->config->method('getValueString')->willReturn('');
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Register not configured for ability');
        $this->fetcher->getObjects('ability');
    }

    public function testThrowsWhenSchemaNotConfigured(): void
    {
        $this->appManager->method('getInstalledApps')->willReturn(['openregister']);
        $this->container->method('get')->willReturn(new \stdClass());
        $this->config->method('getValueString')->willReturnCallback(
            function (string $app, string $key, string $default) {
                if ($key === 'ability_register') return 'reg-1';
                return '';
            }
        );
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Schema not configured for ability');
        $this->fetcher->getObjects('ability');
    }

    public function testThrowsWhenOpenRegisterNotInstalled(): void
    {
        $this->appManager->method('getInstalledApps')->willReturn([]);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('OpenRegister app is not installed');
        $this->fetcher->getObjects('skill');
    }

    public function testGetObjectCleansUriId(): void
    {
        $receivedId = '';
        $mapperMock = new class {
            public string $lastId = '';
            public function find(string $id): array { $this->lastId = $id; return ['id' => $id]; }
            public function findAll($l=null,$o=null,$f=[],$s=[],$sr=null): array { return []; }
        };
        $this->setupOpenRegister($mapperMock);
        $this->configureType('character');
        $this->fetcher->getObject('character', 'https://example.com/api/objects/character/my-uuid');
        self::assertSame('my-uuid', $mapperMock->lastId);
    }

    public function testGetObjectSimpleId(): void
    {
        $mapperMock = new class {
            public string $lastId = '';
            public function find(string $id): array { $this->lastId = $id; return ['id' => $id]; }
            public function findAll($l=null,$o=null,$f=[],$s=[],$sr=null): array { return []; }
        };
        $this->setupOpenRegister($mapperMock);
        $this->configureType('character');
        $this->fetcher->getObject('character', 'abc-123');
        self::assertSame('abc-123', $mapperMock->lastId);
    }

    public function testConvertsJsonSerializableToArray(): void
    {
        $entity = new class implements \JsonSerializable {
            public function jsonSerialize(): array { return ['id' => 1, 'name' => 'Strength']; }
        };
        $mapperMock = new class($entity) {
            private object $e;
            public function __construct(object $e) { $this->e = $e; }
            public function findAll($l=null,$o=null,$f=[],$s=[],$sr=null): array { return [$this->e]; }
        };
        $this->setupOpenRegister($mapperMock);
        $this->configureType('skill');
        $result = $this->fetcher->getObjects('skill');
        self::assertSame(['id' => 1, 'name' => 'Strength'], $result[0]);
    }

    public function testPassesArraysThrough(): void
    {
        $mapperMock = new class {
            public function findAll($l=null,$o=null,$f=[],$s=[],$sr=null): array {
                return [['id' => 'uuid', 'name' => 'Healing']];
            }
        };
        $this->setupOpenRegister($mapperMock);
        $this->configureType('skill');
        $result = $this->fetcher->getObjects('skill');
        self::assertSame(['id' => 'uuid', 'name' => 'Healing'], $result[0]);
    }

    public function testServiceCached(): void
    {
        $this->appManager->method('getInstalledApps')->willReturn(['openregister']);
        $mapperMock = new class {
            public function findAll($l=null,$o=null,$f=[],$s=[],$sr=null): array { return []; }
        };
        $openRegMock = new class($mapperMock) {
            private object $m;
            public function __construct(object $m) { $this->m = $m; }
            public function getMapper(string $r, string $s): object { return $this->m; }
        };
        $this->container->expects($this->once())->method('get')->willReturn($openRegMock);
        $this->config->method('getValueString')->willReturnCallback(
            function (string $app, string $key, string $default) {
                if (str_ends_with($key, '_register')) return 'reg-1';
                if (str_ends_with($key, '_schema')) return 'sch-1';
                return $default;
            }
        );
        $this->fetcher->getObjects('skill');
        $this->fetcher->getObjects('skill');
    }
}
