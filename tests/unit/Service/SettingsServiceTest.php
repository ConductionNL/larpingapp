<?php

/**
 * Unit tests for SettingsService.
 *
 * @category Test
 * @package  OCA\LarpingApp\Tests\Unit\Service
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

namespace OCA\LarpingApp\Tests\Unit\Service;

use OCA\LarpingApp\Service\SettingsLoadService;
use OCA\LarpingApp\Service\SettingsService;
use OCP\IAppConfig;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Tests for SettingsService.
 */
class SettingsServiceTest extends TestCase
{

    /**
     * The service under test.
     *
     * @var SettingsService
     */
    private SettingsService $service;

    /**
     * Mock IAppConfig.
     *
     * @var IAppConfig&MockObject
     */
    private IAppConfig&MockObject $appConfig;

    /**
     * Mock SettingsLoadService.
     *
     * @var SettingsLoadService&MockObject
     */
    private SettingsLoadService&MockObject $settingsLoadService;

    /**
     * Mock LoggerInterface.
     *
     * @var LoggerInterface&MockObject
     */
    private LoggerInterface&MockObject $logger;

    /**
     * Set up test fixtures.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->appConfig           = $this->createMock(IAppConfig::class);
        $this->settingsLoadService = $this->createMock(SettingsLoadService::class);
        $this->logger              = $this->createMock(LoggerInterface::class);

        $this->service = new SettingsService(
            $this->appConfig,
            $this->settingsLoadService,
            $this->logger,
        );

    }//end setUp()

    /**
     * Test that getSettings() returns all CONFIG_KEYS with defaults.
     *
     * @return void
     */
    public function testGetSettingsReturnsAllConfigKeys(): void
    {
        $this->appConfig->method('getValueString')
            ->willReturn('');

        $result = $this->service->getSettings();

        $expectedKeys = [
            'register',
            'character_schema',
            'player_schema',
            'ability_schema',
            'skill_schema',
            'item_schema',
            'condition_schema',
            'effect_schema',
            'event_schema',
            'setting_schema',
        ];

        foreach ($expectedKeys as $key) {
            self::assertArrayHasKey($key, $result);
        }

        self::assertCount(10, $result);

    }//end testGetSettingsReturnsAllConfigKeys()

    /**
     * Test that getSettings() returns stored values from IAppConfig.
     *
     * @return void
     */
    public function testGetSettingsReturnsStoredValues(): void
    {
        $valueMap = [
            ['larpingapp', 'register', '', '42'],
            ['larpingapp', 'character_schema', '', '101'],
            ['larpingapp', 'player_schema', '', '102'],
            ['larpingapp', 'ability_schema', '', ''],
            ['larpingapp', 'skill_schema', '', ''],
            ['larpingapp', 'item_schema', '', ''],
            ['larpingapp', 'condition_schema', '', ''],
            ['larpingapp', 'effect_schema', '', ''],
            ['larpingapp', 'event_schema', '', ''],
            ['larpingapp', 'setting_schema', '', ''],
        ];
        $this->appConfig->method('getValueString')
            ->willReturnMap($valueMap);

        $result = $this->service->getSettings();

        self::assertSame('42', $result['register']);
        self::assertSame('101', $result['character_schema']);
        self::assertSame('102', $result['player_schema']);

    }//end testGetSettingsReturnsStoredValues()

    /**
     * Test that updateSettings() only updates known CONFIG_KEYS.
     *
     * @return void
     */
    public function testUpdateSettingsOnlyUpdatesKnownKeys(): void
    {
        // Should only set register and character_schema, NOT malicious_key.
        $this->appConfig->expects($this->exactly(2))
            ->method('setValueString');

        $this->appConfig->method('getValueString')
            ->willReturn('');

        $this->service->updateSettings([
            'register'         => 'reg-1',
            'character_schema' => 'sch-1',
            'malicious_key'    => 'evil',
        ]);

    }//end testUpdateSettingsOnlyUpdatesKnownKeys()

    /**
     * Test that updateSettings() returns the updated configuration.
     *
     * @return void
     */
    public function testUpdateSettingsReturnsUpdatedConfig(): void
    {
        $this->appConfig->method('getValueString')
            ->willReturn('new-value');

        $result = $this->service->updateSettings(['register' => 'new-value']);

        self::assertSame('new-value', $result['register']);

    }//end testUpdateSettingsReturnsUpdatedConfig()

    /**
     * Test that updateSettings() logs the update.
     *
     * @return void
     */
    public function testUpdateSettingsLogsUpdate(): void
    {
        $this->appConfig->method('getValueString')
            ->willReturn('');

        $this->logger->expects($this->once())
            ->method('info')
            ->with(
                'LarpingApp settings updated',
                $this->callback(function ($context) {
                    return isset($context['keys']) === true;
                })
            );

        $this->service->updateSettings(['register' => 'val']);

    }//end testUpdateSettingsLogsUpdate()

    /**
     * Test that loadSettings() delegates to SettingsLoadService.
     *
     * @return void
     */
    public function testLoadSettingsDelegates(): void
    {
        $expected = ['registers' => [], 'schemas' => []];

        $this->settingsLoadService->expects($this->once())
            ->method('loadSettings')
            ->with(force: false)
            ->willReturn($expected);

        $result = $this->service->loadSettings();

        self::assertSame($expected, $result);

    }//end testLoadSettingsDelegates()

    /**
     * Test that loadSettings() passes force flag through.
     *
     * @return void
     */
    public function testLoadSettingsForceFlag(): void
    {
        $this->settingsLoadService->expects($this->once())
            ->method('loadSettings')
            ->with(force: true)
            ->willReturn([]);

        $this->service->loadSettings(force: true);

    }//end testLoadSettingsForceFlag()

    /**
     * Test that getConfigValue() reads a single key.
     *
     * @return void
     */
    public function testGetConfigValue(): void
    {
        $this->appConfig->expects($this->once())
            ->method('getValueString')
            ->with('larpingapp', 'register', '')
            ->willReturn('42');

        $result = $this->service->getConfigValue('register');

        self::assertSame('42', $result);

    }//end testGetConfigValue()

    /**
     * Test that setConfigValue() writes a single key.
     *
     * @return void
     */
    public function testSetConfigValue(): void
    {
        $this->appConfig->expects($this->once())
            ->method('setValueString')
            ->with('larpingapp', 'register', '99');

        $this->service->setConfigValue('register', '99');

    }//end testSetConfigValue()
}//end class
