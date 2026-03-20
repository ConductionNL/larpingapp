<?php
declare(strict_types=1);
namespace OCA\LarpingApp\Tests\Unit\Service;

use OCA\LarpingApp\Service\CharacterService;
use OCA\LarpingApp\Service\RegisterObjectFetcher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CharacterServiceTest extends TestCase
{
    private RegisterObjectFetcher&MockObject $objectFetcher;
    private CharacterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->objectFetcher = $this->createMock(RegisterObjectFetcher::class);
        $this->objectFetcher->method('getObjects')->willReturn([]);
        $this->service = new CharacterService($this->objectFetcher);
    }

    private function createServiceWithEntities(
        array $abilities = [], array $effects = [], array $skills = [],
        array $items = [], array $conditions = [], array $events = []
    ): CharacterService {
        $fetcher = $this->createMock(RegisterObjectFetcher::class);
        $fetcher->method('getObjects')->willReturnMap([
            ['skill', null, null, [], [], null, $skills],
            ['item', null, null, [], [], null, $items],
            ['condition', null, null, [], [], null, $conditions],
            ['event', null, null, [], [], null, $events],
            ['effect', null, null, [], [], null, $effects],
            ['ability', null, null, [], [], null, $abilities],
        ]);
        return new CharacterService($fetcher);
    }

    public function testCalculateCharacterWithNoAssociations(): void
    {
        $result = $this->service->calculateCharacter(['id' => 'c1', 'skills' => []]);
        self::assertArrayHasKey('stats', $result);
    }

    public function testAbilityInitializationFromBaseValues(): void
    {
        $svc = $this->createServiceWithEntities(abilities: [
            ['id' => 'str', 'name' => 'Strength', 'base' => 10],
            ['id' => 'dex', 'name' => 'Dexterity', 'base' => 8],
        ]);
        $r = $svc->calculateCharacter(['id' => 'c1']);
        self::assertSame(10, $r['stats']['str']['value']);
        self::assertSame(10, $r['stats']['str']['base']);
        self::assertSame('Strength', $r['stats']['str']['name']);
        self::assertSame(8, $r['stats']['dex']['value']);
    }

    public function testPositiveEffectIncreasesAbilityValue(): void
    {
        $svc = $this->createServiceWithEntities(
            abilities: [['id' => 'str', 'name' => 'Str', 'base' => 10]],
            effects: [['id' => 'e1', 'name' => 'Strong', 'modifier' => 5, 'modification' => 'positive', 'abilities' => ['str']]],
            skills: [['id' => 's1', 'name' => 'War', 'effects' => ['e1']]]
        );
        $r = $svc->calculateCharacter(['id' => 'c1', 'skills' => ['s1']]);
        self::assertSame(15, $r['stats']['str']['value']);
    }

    public function testNegativeEffectDecreasesAbilityValue(): void
    {
        $svc = $this->createServiceWithEntities(
            abilities: [['id' => 'hp', 'name' => 'HP', 'base' => 20]],
            effects: [['id' => 'e1', 'name' => 'Poison', 'modifier' => 3, 'modification' => 'negative', 'abilities' => ['hp']]],
            conditions: [['id' => 'c1', 'name' => 'Poisoned', 'effects' => ['e1']]]
        );
        $r = $svc->calculateCharacter(['id' => 'ch1', 'conditions' => ['c1']]);
        self::assertSame(17, $r['stats']['hp']['value']);
    }

    public function testEffectApplicationOrderAndAuditTrail(): void
    {
        $svc = $this->createServiceWithEntities(
            abilities: [['id' => 'hp', 'name' => 'HP', 'base' => 20]],
            effects: [
                ['id' => 'es', 'name' => 'Skill', 'modifier' => 5, 'modification' => 'positive', 'abilities' => ['hp']],
                ['id' => 'ei', 'name' => 'Item', 'modifier' => 3, 'modification' => 'positive', 'abilities' => ['hp']],
                ['id' => 'ec', 'name' => 'Cond', 'modifier' => 2, 'modification' => 'negative', 'abilities' => ['hp']],
                ['id' => 'ee', 'name' => 'Evt', 'modifier' => 1, 'modification' => 'positive', 'abilities' => ['hp']],
            ],
            skills: [['id' => 's1', 'name' => 'S', 'effects' => ['es']]],
            items: [['id' => 'i1', 'name' => 'I', 'effects' => ['ei']]],
            conditions: [['id' => 'c1', 'name' => 'C', 'effects' => ['ec']]],
            events: [['id' => 'v1', 'name' => 'E', 'effects' => ['ee']]]
        );
        $r = $svc->calculateCharacter([
            'id' => 'ch1', 'skills' => ['s1'], 'items' => ['i1'],
            'conditions' => ['c1'], 'events' => ['v1'],
        ]);
        self::assertSame(27, $r['stats']['hp']['value']);
        $a = $r['stats']['hp']['audit'];
        self::assertCount(4, $a);
        self::assertSame(20, $a[0]['old']);
        self::assertSame(25, $a[0]['new']);
        self::assertSame(27, $a[3]['new']);
    }

    public function testMissingEntityReferencesAreSkipped(): void
    {
        $svc = $this->createServiceWithEntities(abilities: [['id' => 'str', 'name' => 'Str', 'base' => 10]]);
        $r = $svc->calculateCharacter(['id' => 'c1', 'skills' => ['nonexistent']]);
        self::assertSame(10, $r['stats']['str']['value']);
    }

    public function testNullEffectIdsAreSkipped(): void
    {
        $svc = $this->createServiceWithEntities(
            abilities: [['id' => 'str', 'name' => 'Str', 'base' => 10]],
            effects: [['id' => 'e1', 'name' => 'Boost', 'modifier' => 5, 'modification' => 'positive', 'abilities' => ['str']]],
            skills: [['id' => 's1', 'name' => 'Mixed', 'effects' => ['e1', null, 'gone']]]
        );
        $r = $svc->calculateCharacter(['id' => 'c1', 'skills' => ['s1']]);
        self::assertSame(15, $r['stats']['str']['value']);
    }

    public function testEffectTargetingMultipleAbilities(): void
    {
        $svc = $this->createServiceWithEntities(
            abilities: [['id' => 'a1', 'name' => 'Arc', 'base' => 0], ['id' => 'a2', 'name' => 'Spi', 'base' => 0]],
            effects: [['id' => 'e1', 'name' => 'Boost', 'modifier' => 3, 'modification' => 'positive', 'abilities' => ['a1', 'a2']]],
            skills: [['id' => 's1', 'name' => 'Med', 'effects' => ['e1']]]
        );
        $r = $svc->calculateCharacter(['id' => 'c1', 'skills' => ['s1']]);
        self::assertSame(3, $r['stats']['a1']['value']);
        self::assertSame(3, $r['stats']['a2']['value']);
    }

    public function testLegacyStatIdField(): void
    {
        $svc = $this->createServiceWithEntities(
            abilities: [['id' => 'dex', 'name' => 'Dex', 'base' => 10]],
            effects: [['id' => 'e1', 'name' => 'Quick', 'modifier' => 2, 'modification' => 'positive', 'stat_id' => 'dex']],
            skills: [['id' => 's1', 'name' => 'Agility', 'effects' => ['e1']]]
        );
        $r = $svc->calculateCharacter(['id' => 'c1', 'skills' => ['s1']]);
        self::assertSame(12, $r['stats']['dex']['value']);
    }

    public function testCharacterWithMissingAssociationKeys(): void
    {
        $svc = $this->createServiceWithEntities(abilities: [['id' => 'str', 'name' => 'Str', 'base' => 10]]);
        $r = $svc->calculateCharacter(['id' => 'c1', 'name' => 'Minimal']);
        self::assertSame(10, $r['stats']['str']['value']);
        self::assertEmpty($r['stats']['str']['audit']);
    }
}
