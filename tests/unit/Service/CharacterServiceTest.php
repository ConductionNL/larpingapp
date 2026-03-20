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
        $abilities = [
            ['id' => 'ability-xp', 'name' => 'XP', 'base' => 0],
            ['id' => 'ability-rep', 'name' => 'Reputation', 'base' => 0],
            ['id' => 'ability-str', 'name' => 'Strength', 'base' => 10],
            ['id' => 'ability-mana', 'name' => 'Mana', 'base' => 5],
        ];
        $effects = [
            ['id' => 'eff-xp50', 'name' => '+50 XP', 'modifier' => 50, 'modification' => 'positive', 'abilities' => ['ability-xp'], 'stat_id' => null],
            ['id' => 'eff-rep10', 'name' => '+10 Rep', 'modifier' => 10, 'modification' => 'positive', 'abilities' => ['ability-rep'], 'stat_id' => null],
            ['id' => 'eff-rep5', 'name' => '+5 Rep', 'modifier' => 5, 'modification' => 'positive', 'abilities' => ['ability-rep'], 'stat_id' => null],
            ['id' => 'eff-str3', 'name' => '+3 Str', 'modifier' => 3, 'modification' => 'positive', 'abilities' => [], 'stat_id' => 'ability-str'],
            ['id' => 'eff-strneg2', 'name' => '-2 Str', 'modifier' => 2, 'modification' => 'negative', 'abilities' => ['ability-str'], 'stat_id' => null],
        ];
        $events = [
            ['id' => 'evt-tourn', 'name' => 'Tournament', 'effects' => ['eff-xp50']],
            ['id' => 'evt-battle', 'name' => 'Battle', 'effects' => ['eff-rep10']],
            ['id' => 'evt-council', 'name' => 'Council', 'effects' => ['eff-rep5']],
            ['id' => 'evt-social', 'name' => 'Social', 'effects' => []],
        ];
        $skills = [['id' => 'sk-sword', 'name' => 'Sword', 'effects' => ['eff-str3']]];
        $items = [['id' => 'it-shield', 'name' => 'Shield', 'effects' => []]];
        $conditions = [['id' => 'co-cursed', 'name' => 'Cursed', 'effects' => ['eff-strneg2']]];
        $this->objectFetcher->method('getObjects')->willReturnCallback(
            function (string $type) use ($skills, $items, $conditions, $events, $effects, $abilities) {
                return match ($type) {
                    'skill' => $skills, 'item' => $items, 'condition' => $conditions,
                    'event' => $events, 'effect' => $effects, 'ability' => $abilities, default => [],
                };
            }
        );
        $this->service = new CharacterService(objectFetcher: $this->objectFetcher);
    }
    public function testEventEffectsApplied(): void
    {
        $r = $this->service->calculateCharacter(character: ['id'=>'c1','name'=>'F','skills'=>[],'items'=>[],'conditions'=>[],'events'=>['evt-tourn']]);
        self::assertSame(50, $r['stats']['ability-xp']['value']);
        self::assertCount(1, $r['stats']['ability-xp']['audit']);
    }
    public function testMultipleEventEffectsStack(): void
    {
        $r = $this->service->calculateCharacter(character: ['id'=>'c2','name'=>'A','skills'=>[],'items'=>[],'conditions'=>[],'events'=>['evt-battle','evt-council']]);
        self::assertSame(15, $r['stats']['ability-rep']['value']);
    }
    public function testEventNoEffectsSkipped(): void
    {
        $r = $this->service->calculateCharacter(character: ['id'=>'c3','name'=>'B','skills'=>[],'items'=>[],'conditions'=>[],'events'=>['evt-social']]);
        self::assertSame(0, $r['stats']['ability-xp']['value']);
        self::assertSame(10, $r['stats']['ability-str']['value']);
    }
    public function testMissingEventSkipped(): void
    {
        $r = $this->service->calculateCharacter(character: ['id'=>'c4','name'=>'O','skills'=>[],'items'=>[],'conditions'=>[],'events'=>['nonexist']]);
        self::assertSame(10, $r['stats']['ability-str']['value']);
    }
    public function testEffectOrder(): void
    {
        $r = $this->service->calculateCharacter(character: ['id'=>'c5','name'=>'M','skills'=>['sk-sword'],'items'=>[],'conditions'=>['co-cursed'],'events'=>['evt-tourn']]);
        self::assertSame(11, $r['stats']['ability-str']['value']);
        self::assertSame(50, $r['stats']['ability-xp']['value']);
    }
    public function testNoEventsProperty(): void
    {
        $r = $this->service->calculateCharacter(character: ['id'=>'c6','name'=>'N','skills'=>[],'items'=>[],'conditions'=>[]]);
        self::assertSame(0, $r['stats']['ability-xp']['value']);
    }
    public function testEmptyEventsArray(): void
    {
        $r = $this->service->calculateCharacter(character: ['id'=>'c7','name'=>'E','skills'=>[],'items'=>[],'conditions'=>[],'events'=>[]]);
        self::assertArrayHasKey('stats', $r);
    }
    public function testStatsStructure(): void
    {
        $r = $this->service->calculateCharacter(character: ['id'=>'c8','name'=>'T','skills'=>[],'items'=>[],'conditions'=>[],'events'=>[]]);
        self::assertArrayHasKey('stats', $r);
        self::assertArrayHasKey('ability-xp', $r['stats']);
        self::assertArrayHasKey('name', $r['stats']['ability-xp']);
        self::assertArrayHasKey('base', $r['stats']['ability-xp']);
        self::assertArrayHasKey('value', $r['stats']['ability-xp']);
        self::assertArrayHasKey('audit', $r['stats']['ability-xp']);
    }
}
