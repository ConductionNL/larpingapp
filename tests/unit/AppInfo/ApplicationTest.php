<?php

/**
 * Unit tests for Application class.
 *
 * @category Test
 * @package  OCA\LarpingApp\Tests\Unit\AppInfo
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

namespace OCA\LarpingApp\Tests\Unit\AppInfo;

use OCA\LarpingApp\AppInfo\Application;
use OCP\AppFramework\Bootstrap\IBootstrap;
use PHPUnit\Framework\TestCase;

/**
 * Tests for Application class.
 */
class ApplicationTest extends TestCase
{

    /**
     * Test that APP_ID constant is correctly defined.
     *
     * @return void
     */
    public function testAppIdConstant(): void
    {
        self::assertSame('larpingapp', Application::APP_ID);

    }//end testAppIdConstant()

    /**
     * Test that Application class implements IBootstrap interface.
     *
     * @return void
     */
    public function testClassImplementsIBootstrap(): void
    {
        $interfaces = class_implements(Application::class);

        self::assertContains(IBootstrap::class, $interfaces);

    }//end testClassImplementsIBootstrap()

    /**
     * Test that register method exists and is callable.
     *
     * @return void
     */
    public function testRegisterMethodExists(): void
    {
        self::assertTrue(method_exists(Application::class, 'register'));

    }//end testRegisterMethodExists()

    /**
     * Test that boot method exists and is callable.
     *
     * @return void
     */
    public function testBootMethodExists(): void
    {
        self::assertTrue(method_exists(Application::class, 'boot'));

    }//end testBootMethodExists()
}//end class
