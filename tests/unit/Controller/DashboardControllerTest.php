<?php

/**
 * Unit tests for DashboardController.
 *
 * @category Test
 * @package  OCA\LarpingApp\Tests\Unit\Controller
 *
 * @author    Ruben Linde <ruben@larpingapp.com>
 * @copyright 2024 Ruben Linde
 * @license   AGPL-3.0-or-later https://www.gnu.org/licenses/agpl-3.0.en.html
 *
 * @version GIT: <git-id>
 *
 * @link https://larpingapp.com
 */

declare(strict_types=1);

namespace OCA\LarpingApp\Tests\Unit\Controller;

use OCA\LarpingApp\Controller\DashboardController;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IAppConfig;
use OCP\IRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Tests for DashboardController.
 */
class DashboardControllerTest extends TestCase
{

    /**
     * The controller under test.
     *
     * @var DashboardController
     */
    private DashboardController $controller;

    /**
     * Mock IRequest.
     *
     * @var IRequest&MockObject
     */
    private IRequest&MockObject $request;

    /**
     * Mock IAppConfig.
     *
     * @var IAppConfig&MockObject
     */
    private IAppConfig&MockObject $config;

    /**
     * Set up test fixtures.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = $this->createMock(IRequest::class);
        $this->config  = $this->createMock(IAppConfig::class);

        $this->controller = new DashboardController(
            'larpingapp',
            $this->request,
            $this->config,
        );

    }//end setUp()

    /**
     * Test that page() returns a TemplateResponse.
     *
     * @return void
     */
    public function testPageReturnsTemplateResponse(): void
    {
        $result = $this->controller->page();

        self::assertInstanceOf(TemplateResponse::class, $result);

    }//end testPageReturnsTemplateResponse()

    /**
     * Test that page() uses the 'index' template name.
     *
     * @return void
     */
    public function testPageUsesIndexTemplate(): void
    {
        $result = $this->controller->page();

        self::assertSame('index', $result->getTemplateName());

    }//end testPageUsesIndexTemplate()

    /**
     * Test that page() returns a render=user TemplateResponse.
     *
     * @return void
     */
    public function testPageReturnsUserRenderType(): void
    {
        $result = $this->controller->page();

        self::assertSame(TemplateResponse::RENDER_AS_USER, $result->getRenderAs());

    }//end testPageReturnsUserRenderType()

    /**
     * Test that page() returns empty params.
     *
     * @return void
     */
    public function testPageReturnsEmptyParams(): void
    {
        $result = $this->controller->page();

        self::assertSame([], $result->getParams());

    }//end testPageReturnsEmptyParams()
}//end class
