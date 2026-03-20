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

    public function testGetObjectsThrowsWhenOpenRegisterNotInstalled(): void
    {
        $this->appManager->method('getInstalledApps')->willReturn([]);
        $this->config->method('getValueString')->willReturn('reg-1');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('OpenRegister app is not installed');

        $this->fetcher->getObjects('skill');
    }

    public function testGetObjectsThrowsWhenRegisterNotConfigured(): void
    {
        $this->appManager->method('getInstalledApps')->willReturn(['openregister']);

        $mockService = new class {
            public function getMapper(string $r, string $s): object
            {
                return new class { public function findAll(): array { return []; } };
            }
        };
        $this->container->method('get')->willReturn($mockService);
        $this->config->method('getValueString')->willReturn('');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Register not configured for skill');

        $this->fetcher->getObjects('skill');
    }

    public function testGetObjectsThrowsWhenSchemaNotConfigured(): void
    {
        $this->appManager->method('getInstalledApps')->willReturn(['openregister']);

        $mockService = new class {
            public function getMapper(string $r, string $s): object
            {
                return new class { public function findAll(): array { return []; } };
            }
        };
        $this->container->method('get')->willReturn($mockService);

        $this->config->method('getValueString')
            ->willReturnCallback(function (string $app, string $key, string $default) {
                if (str_ends_with($key, '_register')) {
                    return 'reg-1';
                }
                return '';
            });

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Schema not configured for skill');

        $this->fetcher->getObjects('skill');
    }

    public function testGetObjectReturnsArrayFromMapper(): void
    {
        $this->appManager->method('getInstalledApps')->willReturn(['openregister']);

        $mockMapper = new class {
            public function find(string $id): array { return ['id' => $id, 'name' => 'Test']; }
        };
        $mockService = new class($mockMapper) {
            private object $m;
            public function __construct(object $m) { $this->m = $m; }
            public function getMapper(string $r, string $s): object { return $this->m; }
        };
        $this->container->method('get')->willReturn($mockService);
        $this->config->method('getValueString')
            ->willReturnCallback(function (string $app, string $key, string $default) {
                if (str_ends_with($key, '_register')) { return 'reg-1'; }
                if (str_ends_with($key, '_schema')) { return 'sch-1'; }
                return $default;
            });

        $result = $this->fetcher->getObject('skill', 'skill-123');
        self::assertSame('skill-123', $result['id']);
        self::assertSame('Test', $result['name']);
    }

    public function testGetObjectCleansUrlFormattedId(): void
    {
        $this->appManager->method('getInstalledApps')->willReturn(['openregister']);

        $mockMapper = new class {
            public string $lastId = '';
            public function find(string $id): array { $this->lastId = $id; return ['id' => $id]; }
        };
        $mockService = new class($mockMapper) {
            public object $m;
            public function __construct(object $m) { $this->m = $m; }
            public function getMapper(string $r, string $s): object { return $this->m; }
        };
        $this->container->method('get')->willReturn($mockService);
        $this->config->method('getValueString')
            ->willReturnCallback(function (string $app, string $key, string $default) {
                if (str_ends_with($key, '_register')) { return 'reg-1'; }
                if (str_ends_with($key, '_schema')) { return 'sch-1'; }
                return $default;
            });

        $this->fetcher->getObject('skill', 'https://example.com/api/objects/abc-123');
        self::assertSame('abc-123', $mockMapper->lastId);
    }

    public function testGetObjectsReturnsArrayOfArrays(): void
    {
        $this->appManager->method('getInstalledApps')->willReturn(['openregister']);

        $mockMapper = new class {
            public function findAll($l = null, $o = null, $f = null, $s = null, $sr = null): array {
                return [['id' => '1', 'name' => 'A'], ['id' => '2', 'name' => 'B']];
            }
        };
        $mockService = new class($mockMapper) {
            private object $m;
            public function __construct(object $m) { $this->m = $m; }
            public function getMapper(string $r, string $s): object { return $this->m; }
        };
        $this->container->method('get')->willReturn($mockService);
        $this->config->method('getValueString')
            ->willReturnCallback(function (string $app, string $key, string $default) {
                if (str_ends_with($key, '_register')) { return 'reg-1'; }
                if (str_ends_with($key, '_schema')) { return 'sch-1'; }
                return $default;
            });

        $result = $this->fetcher->getObjects('skill');
        self::assertCount(2, $result);
        self::assertSame('A', $result[0]['name']);
    }
}
