<?php

/**
 * Unit tests for Player entity.
 *
 * @category Test
 * @package  OCA\LarpingApp\Tests\Unit\Db
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

namespace OCA\LarpingApp\Tests\Unit\Db;

use OCA\LarpingApp\Db\Player;
use PHPUnit\Framework\TestCase;

/**
 * Tests for Player entity serialization and field handling.
 *
 * Covers spec requirements PLR-001 through PLR-008 and EVP-015.
 */
class PlayerTest extends TestCase
{

    /**
     * Test Player jsonSerialize includes all expected fields (EVP-015).
     *
     * @return void
     */
    public function testJsonSerializeIncludesAllFields(): void
    {
        $player = new Player();
        $json   = $player->jsonSerialize();

        $expectedFields = ['id', 'name', 'description'];
        foreach ($expectedFields as $field) {
            self::assertArrayHasKey($field, $json, "Missing field: $field");
        }

        self::assertCount(3, $json, 'Player should have exactly 3 fields (skeletal entity)');

    }//end testJsonSerializeIncludesAllFields()

    /**
     * Test Player getJsonFields returns expected field list.
     *
     * @return void
     */
    public function testGetJsonFieldsReturnsExpectedFields(): void
    {
        $player = new Player();
        $fields = $player->getJsonFields();

        self::assertSame(['id', 'name', 'description'], $fields);

    }//end testGetJsonFieldsReturnsExpectedFields()

    /**
     * Test Player name can be set and retrieved (PLR-001, PLR-008).
     *
     * @return void
     */
    public function testPlayerNameSetAndGet(): void
    {
        $player = new Player();
        $player->setName('John Doe');

        self::assertSame('John Doe', $player->getName());

        $json = $player->jsonSerialize();
        self::assertSame('John Doe', $json['name']);

    }//end testPlayerNameSetAndGet()

    /**
     * Test Player description can be set and retrieved.
     *
     * @return void
     */
    public function testPlayerDescriptionSetAndGet(): void
    {
        $player = new Player();
        $player->setDescription('Experienced LARP player');

        self::assertSame('Experienced LARP player', $player->getDescription());

    }//end testPlayerDescriptionSetAndGet()

    /**
     * Test Player hydration from array data.
     *
     * @return void
     */
    public function testHydrateFromArray(): void
    {
        $player = new Player();
        $player->hydrate([
            'name' => 'Alice Smith',
            'description' => 'New player',
        ]);

        $json = $player->jsonSerialize();

        self::assertSame('Alice Smith', $json['name']);
        self::assertSame('New player', $json['description']);

    }//end testHydrateFromArray()

    /**
     * Test Player default values are null.
     *
     * @return void
     */
    public function testDefaultValuesAreNull(): void
    {
        $player = new Player();
        $json   = $player->jsonSerialize();

        self::assertNull($json['name']);
        self::assertNull($json['description']);

    }//end testDefaultValuesAreNull()

}//end class
