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
 * Tests for CharacterService stat calculation engine.
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
     * Set up test fixtures with preloaded entities.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->objectFetcher = $this->createMock(RegisterObjectFetcher::class);

        $this->objectFetcher->method('getObjects')
            ->willReturn([]);

        $this->service = new CharacterService($this->objectFetcher);

    }//end setUp()

    /**
     * Helper to create a service with specific preloaded entities.
     *
     * @param array $abilities   Abilities to preload.
     * @param array $effects     Effects to preload.
     * @param array $skills      Skills to preload.
     * @param array $items       Items to preload.
     * @param array $conditions  Conditions to preload.
     * @param array $events      Events to preload.
     *
     * @return CharacterService
     */
    private function createServiceWithEntities(
        array $abilities = [],
        array $effects = [],
        array $skills = [],
        array $items = [],
        array $conditions = [],
        array $events = []
    ): CharacterService {
        $fetcher = $this->createMock(RegisterObjectFetcher::class);

        $returnMap = [
            ['skill', null, null, [], [], null, $skills],
            ['item', null, null, [], [], null, $items],
            ['condition', null, null, [], [], null, $conditions],
            ['event', null, null, [], [], null, $events],
            ['effect', null, null, [], [], null, $effects],
            ['ability', null, null, [], [], null, $abilities],
        ];

        $fetcher->method('getObjects')
            ->willReturnMap($returnMap);

        return new CharacterService($fetcher);

    }//end createServiceWithEntities()

    /**
     * Test that calculateCharacter returns stats with empty character.
     *
     * @return void
     */
    public function testCalculateCharacterWithNoAssociations(): void
    {
        $character = ['id' => 'char-1', 'name' => 'Test', 'skills' => [], 'items' => []];

        $result = $this->service->calculateCharacter($character);

        self::assertArrayHasKey('stats', $result);
        self::assertIsArray($result['stats']);

    }//end testCalculateCharacterWithNoAssociations()

    /**
     * Test that abilities are initialized with base values.
     *
     * @return void
     */
    public function testAbilityInitializationFromBaseValues(): void
    {
        $abilities = [
            ['id' => 'str-1', 'name' => 'Strength', 'base' => 10],
            ['id' => 'dex-1', 'name' => 'Dexterity', 'base' => 8],
            ['id' => 'mana-1', 'name' => 'Mana', 'base' => 0],
        ];

        $service   = $this->createServiceWithEntities(abilities: $abilities);
        $character = ['id' => 'char-1', 'name' => 'Test'];

        $result = $service->calculateCharacter($character);

        self::assertSame(10, $result['stats']['str-1']['value']);
        self::assertSame(10, $result['stats']['str-1']['base']);
        self::assertSame('Strength', $result['stats']['str-1']['name']);
        self::assertSame(8, $result['stats']['dex-1']['value']);
        self::assertSame(0, $result['stats']['mana-1']['value']);

    }//end testAbilityInitializationFromBaseValues()

    /**
     * Test positive effect application increases ability value.
     *
     * @return void
     */
    public function testPositiveEffectIncreasesAbilityValue(): void
    {
        $abilities = [['id' => 'str-1', 'name' => 'Strength', 'base' => 10]];
        $effects   = [
            ['id' => 'eff-1', 'name' => 'Strong Arm', 'modifier' => 5, 'modification' => 'positive', 'abilities' => ['str-1']],
        ];
        $skills = [['id' => 'skill-1', 'name' => 'Warrior Training', 'effects' => ['eff-1']]];

        $service   = $this->createServiceWithEntities(abilities: $abilities, effects: $effects, skills: $skills);
        $character = ['id' => 'char-1', 'name' => 'Conan', 'skills' => ['skill-1']];

        $result = $service->calculateCharacter($character);

        self::assertSame(15, $result['stats']['str-1']['value']);

    }//end testPositiveEffectIncreasesAbilityValue()

    /**
     * Test negative effect application decreases ability value.
     *
     * @return void
     */
    public function testNegativeEffectDecreasesAbilityValue(): void
    {
        $abilities  = [['id' => 'hp-1', 'name' => 'HP', 'base' => 20]];
        $effects    = [
            ['id' => 'eff-1', 'name' => 'Poison', 'modifier' => 3, 'modification' => 'negative', 'abilities' => ['hp-1']],
        ];
        $conditions = [['id' => 'cond-1', 'name' => 'Poisoned', 'effects' => ['eff-1']]];

        $service   = $this->createServiceWithEntities(abilities: $abilities, effects: $effects, conditions: $conditions);
        $character = ['id' => 'char-1', 'name' => 'Warrior', 'conditions' => ['cond-1']];

        $result = $service->calculateCharacter($character);

        self::assertSame(17, $result['stats']['hp-1']['value']);

    }//end testNegativeEffectDecreasesAbilityValue()

    /**
     * Test effect application order: skills, items, conditions, events.
     *
     * @return void
     */
    public function testEffectApplicationOrder(): void
    {
        $abilities  = [['id' => 'hp-1', 'name' => 'HP', 'base' => 20]];
        $effects    = [
            ['id' => 'eff-s', 'name' => 'Skill HP', 'modifier' => 5, 'modification' => 'positive', 'abilities' => ['hp-1']],
            ['id' => 'eff-i', 'name' => 'Item HP', 'modifier' => 3, 'modification' => 'positive', 'abilities' => ['hp-1']],
            ['id' => 'eff-c', 'name' => 'Cond HP', 'modifier' => 2, 'modification' => 'negative', 'abilities' => ['hp-1']],
            ['id' => 'eff-e', 'name' => 'Event HP', 'modifier' => 1, 'modification' => 'positive', 'abilities' => ['hp-1']],
        ];
        $skills     = [['id' => 'skill-1', 'name' => 'S', 'effects' => ['eff-s']]];
        $items      = [['id' => 'item-1', 'name' => 'I', 'effects' => ['eff-i']]];
        $conditions = [['id' => 'cond-1', 'name' => 'C', 'effects' => ['eff-c']]];
        $events     = [['id' => 'evt-1', 'name' => 'E', 'effects' => ['eff-e']]];

        $service   = $this->createServiceWithEntities(
            abilities: $abilities,
            effects: $effects,
            skills: $skills,
            items: $items,
            conditions: $conditions,
            events: $events
        );
        $character = [
            'id'         => 'char-1',
            'skills'     => ['skill-1'],
            'items'      => ['item-1'],
            'conditions' => ['cond-1'],
            'events'     => ['evt-1'],
        ];

        $result = $service->calculateCharacter($character);

        self::assertSame(27, $result['stats']['hp-1']['value']);

        $audit = $result['stats']['hp-1']['audit'];
        self::assertCount(4, $audit);
        self::assertSame(20, $audit[0]['old']);
        self::assertSame(25, $audit[0]['new']);
        self::assertSame(25, $audit[1]['old']);
        self::assertSame(28, $audit[1]['new']);
        self::assertSame(28, $audit[2]['old']);
        self::assertSame(26, $audit[2]['new']);
        self::assertSame(26, $audit[3]['old']);
        self::assertSame(27, $audit[3]['new']);

    }//end testEffectApplicationOrder()

    /**
     * Test that missing entity references are gracefully skipped.
     *
     * @return void
     */
    public function testMissingEntityReferencesAreSkipped(): void
    {
        $abilities = [['id' => 'str-1', 'name' => 'Strength', 'base' => 10]];

        $service   = $this->createServiceWithEntities(abilities: $abilities);
        $character = ['id' => 'char-1', 'skills' => ['nonexistent-uuid']];

        $result = $service->calculateCharacter($character);

        self::assertSame(10, $result['stats']['str-1']['value']);

    }//end testMissingEntityReferencesAreSkipped()

    /**
     * Test that null effect IDs in entity effects array are skipped.
     *
     * @return void
     */
    public function testNullEffectIdsAreSkipped(): void
    {
        $abilities = [['id' => 'str-1', 'name' => 'Strength', 'base' => 10]];
        $effects   = [
            ['id' => 'eff-1', 'name' => 'Boost', 'modifier' => 5, 'modification' => 'positive', 'abilities' => ['str-1']],
        ];
        $skills = [['id' => 'skill-1', 'name' => 'Mixed', 'effects' => ['eff-1', null, 'nonexistent']]];

        $service   = $this->createServiceWithEntities(abilities: $abilities, effects: $effects, skills: $skills);
        $character = ['id' => 'char-1', 'skills' => ['skill-1']];

        $result = $service->calculateCharacter($character);

        self::assertSame(15, $result['stats']['str-1']['value']);

    }//end testNullEffectIdsAreSkipped()

    /**
     * Test effect targeting multiple abilities.
     *
     * @return void
     */
    public function testEffectTargetingMultipleAbilities(): void
    {
        $abilities = [
            ['id' => 'arc-1', 'name' => 'Arcane Mana', 'base' => 0],
            ['id' => 'spi-1', 'name' => 'Spiritual Mana', 'base' => 0],
        ];
        $effects = [
            ['id' => 'eff-1', 'name' => 'Universal Boost', 'modifier' => 3, 'modification' => 'positive', 'abilities' => ['arc-1', 'spi-1']],
        ];
        $skills = [['id' => 'skill-1', 'name' => 'Meditation', 'effects' => ['eff-1']]];

        $service   = $this->createServiceWithEntities(abilities: $abilities, effects: $effects, skills: $skills);
        $character = ['id' => 'char-1', 'skills' => ['skill-1']];

        $result = $service->calculateCharacter($character);

        self::assertSame(3, $result['stats']['arc-1']['value']);
        self::assertSame(3, $result['stats']['spi-1']['value']);

    }//end testEffectTargetingMultipleAbilities()

    /**
     * Test legacy stat_id field on effect.
     *
     * @return void
     */
    public function testLegacyStatIdField(): void
    {
        $abilities = [['id' => 'dex-1', 'name' => 'Dexterity', 'base' => 10]];
        $effects   = [
            ['id' => 'eff-1', 'name' => 'Quick', 'modifier' => 2, 'modification' => 'positive', 'stat_id' => 'dex-1'],
        ];
        $skills = [['id' => 'skill-1', 'name' => 'Agility', 'effects' => ['eff-1']]];

        $service   = $this->createServiceWithEntities(abilities: $abilities, effects: $effects, skills: $skills);
        $character = ['id' => 'char-1', 'skills' => ['skill-1']];

        $result = $service->calculateCharacter($character);

        self::assertSame(12, $result['stats']['dex-1']['value']);

    }//end testLegacyStatIdField()

    /**
     * Test audit trail records effect data correctly.
     *
     * @return void
     */
    public function testAuditTrailRecordsEffectData(): void
    {
        $abilities = [['id' => 'str-1', 'name' => 'Strength', 'base' => 10]];
        $effects   = [
            ['id' => 'eff-1', 'name' => 'Power', 'modifier' => 5, 'modification' => 'positive', 'abilities' => ['str-1']],
        ];
        $skills = [['id' => 'skill-1', 'name' => 'Train', 'effects' => ['eff-1']]];

        $service   = $this->createServiceWithEntities(abilities: $abilities, effects: $effects, skills: $skills);
        $character = ['id' => 'char-1', 'skills' => ['skill-1']];

        $result = $service->calculateCharacter($character);
        $audit  = $result['stats']['str-1']['audit'][0];

        self::assertSame('effect', $audit['type']);
        self::assertSame(10, $audit['old']);
        self::assertSame(15, $audit['new']);
        self::assertSame('Power', $audit['effect']['name']);

    }//end testAuditTrailRecordsEffectData()
}//end class
