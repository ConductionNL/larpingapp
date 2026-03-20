<?php

/**
 * Unit tests for CharacterService.
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

use OCA\LarpingApp\Service\CharacterService;
use OCA\LarpingApp\Service\RegisterObjectFetcher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Tests for CharacterService stat calculation with events and effects.
 *
 * Covers spec requirements EVT-008, EVT-020 through EVT-023, and
 * the event effect application scenarios from events-players/spec.md.
 */
class CharacterServiceTest extends TestCase
{

    /**
     * Mock RegisterObjectFetcher.
     *
     * @var RegisterObjectFetcher&MockObject
     */
    private RegisterObjectFetcher&MockObject $objectFetcher;

    /**
     * The service under test.
     *
     * @var CharacterService
     */
    private CharacterService $service;

    /**
     * Set up test fixtures with sample game entities.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->objectFetcher = $this->createMock(RegisterObjectFetcher::class);

        // Define sample entities for the test world.
        $abilities = [
            ['id' => 'ability-xp', 'name' => 'XP', 'base' => 0],
            ['id' => 'ability-reputation', 'name' => 'Reputation', 'base' => 0],
            ['id' => 'ability-strength', 'name' => 'Strength', 'base' => 10],
            ['id' => 'ability-mana', 'name' => 'Mana', 'base' => 5],
        ];

        $effects = [
            [
                'id' => 'effect-xp50',
                'name' => '+50 XP',
                'modifier' => 50,
                'modification' => 'positive',
                'abilities' => ['ability-xp'],
                'stat_id' => null,
            ],
            [
                'id' => 'effect-rep10',
                'name' => '+10 Reputation',
                'modifier' => 10,
                'modification' => 'positive',
                'abilities' => ['ability-reputation'],
                'stat_id' => null,
            ],
            [
                'id' => 'effect-rep5',
                'name' => '+5 Reputation',
                'modifier' => 5,
                'modification' => 'positive',
                'abilities' => ['ability-reputation'],
                'stat_id' => null,
            ],
            [
                'id' => 'effect-mana2',
                'name' => '+2 Mana',
                'modifier' => 2,
                'modification' => 'positive',
                'abilities' => ['ability-mana'],
                'stat_id' => null,
            ],
            [
                'id' => 'effect-str3',
                'name' => '+3 Strength',
                'modifier' => 3,
                'modification' => 'positive',
                'abilities' => [],
                'stat_id' => 'ability-strength',
            ],
            [
                'id' => 'effect-str-neg2',
                'name' => '-2 Strength',
                'modifier' => 2,
                'modification' => 'negative',
                'abilities' => ['ability-strength'],
                'stat_id' => null,
            ],
        ];

        $events = [
            [
                'id' => 'event-tournament',
                'name' => 'Tournament',
                'effects' => ['effect-xp50'],
            ],
            [
                'id' => 'event-battle',
                'name' => 'Battle of Helm\'s Deep',
                'effects' => ['effect-rep10'],
            ],
            [
                'id' => 'event-council',
                'name' => 'Council of Elrond',
                'effects' => ['effect-rep5'],
            ],
            [
                'id' => 'event-social',
                'name' => 'Social Gathering',
                'effects' => [],
            ],
            [
                'id' => 'event-blessing',
                'name' => 'Summer LARP',
                'effects' => ['effect-mana2'],
            ],
        ];

        $skills = [
            [
                'id' => 'skill-sword',
                'name' => 'Swordsmanship',
                'effects' => ['effect-str3'],
            ],
        ];

        $items = [
            [
                'id' => 'item-shield',
                'name' => 'Iron Shield',
                'effects' => [],
            ],
        ];

        $conditions = [
            [
                'id' => 'condition-cursed',
                'name' => 'Cursed',
                'effects' => ['effect-str-neg2'],
            ],
        ];

        // Configure the mock to return sample entities.
        $this->objectFetcher->method('getObjects')
            ->willReturnCallback(function (string $type) use ($skills, $items, $conditions, $events, $effects, $abilities) {
                return match ($type) {
                    'skill' => $skills,
                    'item' => $items,
                    'condition' => $conditions,
                    'event' => $events,
                    'effect' => $effects,
                    'ability' => $abilities,
                    'character' => [],
                    default => [],
                };
            });

        $this->service = new CharacterService(
            objectFetcher: $this->objectFetcher
        );

    }//end setUp()

    /**
     * Test that event effects are applied during character stat calculation (EVT-008, EVT-020).
     *
     * @return void
     */
    public function testEventEffectsAppliedDuringCalculation(): void
    {
        $character = [
            'id' => 'char-fighter',
            'name' => 'Fighter',
            'skills' => [],
            'items' => [],
            'conditions' => [],
            'events' => ['event-tournament'],
        ];

        $result = $this->service->calculateCharacter(character: $character);

        self::assertSame(50, $result['stats']['ability-xp']['value']);
        self::assertCount(1, $result['stats']['ability-xp']['audit']);
        self::assertSame('effect', $result['stats']['ability-xp']['audit'][0]['type']);

    }//end testEventEffectsAppliedDuringCalculation()

    /**
     * Test that multiple event effects stack (EVT-021).
     *
     * @return void
     */
    public function testMultipleEventEffectsStack(): void
    {
        $character = [
            'id' => 'char-aragorn',
            'name' => 'Aragorn',
            'skills' => [],
            'items' => [],
            'conditions' => [],
            'events' => ['event-battle', 'event-council'],
        ];

        $result = $this->service->calculateCharacter(character: $character);

        // Reputation base=0 + 10 (battle) + 5 (council) = 15.
        self::assertSame(15, $result['stats']['ability-reputation']['value']);
        self::assertCount(2, $result['stats']['ability-reputation']['audit']);

    }//end testMultipleEventEffectsStack()

    /**
     * Test that events with no effects are skipped without error (EVT-022).
     *
     * @return void
     */
    public function testEventWithNoEffectsSkippedGracefully(): void
    {
        $character = [
            'id' => 'char-bard',
            'name' => 'Bard',
            'skills' => [],
            'items' => [],
            'conditions' => [],
            'events' => ['event-social'],
        ];

        $result = $this->service->calculateCharacter(character: $character);

        // All abilities should retain their base values.
        self::assertSame(0, $result['stats']['ability-xp']['value']);
        self::assertSame(0, $result['stats']['ability-reputation']['value']);
        self::assertSame(10, $result['stats']['ability-strength']['value']);
        self::assertSame(5, $result['stats']['ability-mana']['value']);

    }//end testEventWithNoEffectsSkippedGracefully()

    /**
     * Test that missing events are skipped gracefully (EVT-023).
     *
     * @return void
     */
    public function testMissingEventSkippedGracefully(): void
    {
        $character = [
            'id' => 'char-orphan',
            'name' => 'Orphan',
            'skills' => [],
            'items' => [],
            'conditions' => [],
            'events' => ['event-nonexistent'],
        ];

        $result = $this->service->calculateCharacter(character: $character);

        // No crash, base values retained.
        self::assertSame(10, $result['stats']['ability-strength']['value']);

    }//end testMissingEventSkippedGracefully()

    /**
     * Test that event effects are applied AFTER skills, items, conditions (EVT-021 order).
     *
     * @return void
     */
    public function testEffectsAppliedInCorrectOrder(): void
    {
        $character = [
            'id' => 'char-mixed',
            'name' => 'Mixed',
            'skills' => ['skill-sword'],
            'items' => [],
            'conditions' => ['condition-cursed'],
            'events' => ['event-tournament'],
        ];

        $result = $this->service->calculateCharacter(character: $character);

        // Strength: base=10, +3 (skill-sword), -2 (condition-cursed) = 11.
        self::assertSame(11, $result['stats']['ability-strength']['value']);

        // XP: base=0, +50 (event-tournament) = 50.
        self::assertSame(50, $result['stats']['ability-xp']['value']);

        // Check audit order: skill effect first, then condition, then event effects.
        $strengthAudit = $result['stats']['ability-strength']['audit'];
        self::assertCount(2, $strengthAudit);
        self::assertSame('+3 Strength', $strengthAudit[0]['effect']['name']);
        self::assertSame('-2 Strength', $strengthAudit[1]['effect']['name']);

    }//end testEffectsAppliedInCorrectOrder()

    /**
     * Test character with no events property set (empty/null handling).
     *
     * @return void
     */
    public function testCharacterWithNoEventsProperty(): void
    {
        $character = [
            'id' => 'char-noevents',
            'name' => 'NoEvents',
            'skills' => [],
            'items' => [],
            'conditions' => [],
        ];

        $result = $this->service->calculateCharacter(character: $character);

        // Should not crash, base values retained.
        self::assertSame(0, $result['stats']['ability-xp']['value']);

    }//end testCharacterWithNoEventsProperty()

    /**
     * Test character with empty events array.
     *
     * @return void
     */
    public function testCharacterWithEmptyEventsArray(): void
    {
        $character = [
            'id' => 'char-empty',
            'name' => 'Empty',
            'skills' => [],
            'items' => [],
            'conditions' => [],
            'events' => [],
        ];

        $result = $this->service->calculateCharacter(character: $character);

        // Should not crash.
        self::assertArrayHasKey('stats', $result);

    }//end testCharacterWithEmptyEventsArray()

    /**
     * Test that calculateCharacter returns stats key.
     *
     * @return void
     */
    public function testCalculateCharacterReturnsStats(): void
    {
        $character = [
            'id' => 'char-test',
            'name' => 'Test',
            'skills' => [],
            'items' => [],
            'conditions' => [],
            'events' => [],
        ];

        $result = $this->service->calculateCharacter(character: $character);

        self::assertArrayHasKey('stats', $result);
        self::assertArrayHasKey('ability-xp', $result['stats']);
        self::assertArrayHasKey('ability-strength', $result['stats']);
        self::assertArrayHasKey('name', $result['stats']['ability-xp']);
        self::assertArrayHasKey('base', $result['stats']['ability-xp']);
        self::assertArrayHasKey('value', $result['stats']['ability-xp']);
        self::assertArrayHasKey('audit', $result['stats']['ability-xp']);

    }//end testCalculateCharacterReturnsStats()

}//end class
