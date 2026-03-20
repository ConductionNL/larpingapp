<?php
declare(strict_types=1);
namespace OCA\LarpingApp\Tests\Unit\Controller;
use OCA\LarpingApp\Controller\CharactersController;
use OCA\LarpingApp\Service\CharacterService;
use OCA\LarpingApp\Service\RegisterObjectFetcher;
use OCP\App\IAppManager;
use OCP\AppFramework\Http\DataDownloadResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class CharactersControllerTest extends TestCase
{
    private IRequest&MockObject $request;
    private RegisterObjectFetcher&MockObject $objectFetcher;
    private CharacterService&MockObject $characterService;
    private IAppManager&MockObject $appManager;
    private ContainerInterface&MockObject $container;
    private CharactersController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = $this->createMock(IRequest::class);
        $this->objectFetcher = $this->createMock(RegisterObjectFetcher::class);
        $this->characterService = $this->createMock(CharacterService::class);
        $this->appManager = $this->createMock(IAppManager::class);
        $this->container = $this->createMock(ContainerInterface::class);
        $this->controller = new CharactersController(
            'larpingapp', $this->request, $this->objectFetcher,
            $this->characterService, $this->appManager, $this->container
        );
    }

    public function testReturns424WhenDocuDeskNotInstalled(): void
    {
        $this->appManager->method('isEnabledForUser')->with('docudesk')->willReturn(false);
        $result = $this->controller->downloadPdf('char-1', 'tmpl-1');
        self::assertInstanceOf(JSONResponse::class, $result);
        self::assertSame(424, $result->getStatus());
        self::assertSame('PDF generation requires the DocuDesk app to be installed and enabled', $result->getData()['error']);
    }

    public function testReturns404WhenCharacterNotFound(): void
    {
        $this->appManager->method('isEnabledForUser')->willReturn(true);
        $this->objectFetcher->method('getObject')->willThrowException(new \Exception('Not found'));
        $result = $this->controller->downloadPdf('nonexistent', 'tmpl-1');
        self::assertInstanceOf(JSONResponse::class, $result);
        self::assertSame(404, $result->getStatus());
        self::assertSame('Character not found', $result->getData()['error']);
    }

    public function testReturns404WhenTemplateNotFound(): void
    {
        $this->appManager->method('isEnabledForUser')->willReturn(true);
        $this->objectFetcher->method('getObject')->willReturn(['id' => 'c1', 'name' => 'Merlin']);
        $this->container->method('get')->willThrowException(new \Exception('Not found'));
        $result = $this->controller->downloadPdf('c1', 'bad-tmpl');
        self::assertInstanceOf(JSONResponse::class, $result);
        self::assertSame(404, $result->getStatus());
        self::assertSame('Template not found', $result->getData()['error']);
    }

    public function testReturns500WhenRenderFails(): void
    {
        $this->appManager->method('isEnabledForUser')->willReturn(true);
        $this->objectFetcher->method('getObject')->willReturn(['id' => 'c1', 'name' => 'Merlin']);
        $templateService = new class {
            public function getTemplate(string $id): array {
                return ['content' => '<p>test</p>', 'format' => 'A4', 'orientation' => 'P'];
            }
        };
        $pdfService = new class {
            public function renderPdf(string $templateContent, array $data, array $options): string {
                throw new \Exception('Render error');
            }
        };
        $callCount = 0;
        $this->container->method('get')->willReturnCallback(function () use ($templateService, $pdfService, &$callCount) {
            return (++$callCount === 1) ? $templateService : $pdfService;
        });
        $result = $this->controller->downloadPdf('c1', 'tmpl-1');
        self::assertInstanceOf(JSONResponse::class, $result);
        self::assertSame(500, $result->getStatus());
        self::assertStringContainsString('PDF generation failed', $result->getData()['error']);
    }

    public function testReturnsDataDownloadResponseOnSuccess(): void
    {
        $this->appManager->method('isEnabledForUser')->willReturn(true);
        $this->objectFetcher->method('getObject')->willReturn(['id' => 'c1', 'name' => 'Sir Lancelot']);
        $templateService = new class {
            public function getTemplate(string $id): array {
                return ['content' => '<p>test</p>', 'format' => 'A4', 'orientation' => 'P'];
            }
        };
        $pdfService = new class {
            public function renderPdf(string $templateContent, array $data, array $options): string {
                return '%PDF-fake';
            }
        };
        $callCount = 0;
        $this->container->method('get')->willReturnCallback(function () use ($templateService, $pdfService, &$callCount) {
            return (++$callCount === 1) ? $templateService : $pdfService;
        });
        $result = $this->controller->downloadPdf('c1', 'tmpl-1');
        self::assertInstanceOf(DataDownloadResponse::class, $result);
    }
}
