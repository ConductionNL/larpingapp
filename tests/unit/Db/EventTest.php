<?php

/**
 * Unit tests for Event entity.
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

use OCA\LarpingApp\Db\Event;
use PHPUnit\Framework\TestCase;

/**
 * Tests for Event entity serialization and field handling.
 *
 * Covers spec requirements EVP-010 through EVP-012 (internal storage model).
 */
class EventTest extends TestCase
{

    /**
     * Test that Event uses 'title' field (not 'name') per EVP-010.
     *
     * @return void
     */
    public function testEventUsesTitleNotName(): void
    {
        $event = new Event();
        $event->setTitle('Summer LARP 2025');

        $json = $event->jsonSerialize();

        self::assertArrayHasKey('title', $json);
        self::assertArrayNotHasKey('name', $json);
        self::assertSame('Summer LARP 2025', $json['title']);

    }//end testEventUsesTitleNotName()

    /**
     * Test Event jsonSerialize includes all expected fields (EVP-012).
     *
     * @return void
     */
    public function testJsonSerializeIncludesAllFields(): void
    {
        $event = new Event();
        $json  = $event->jsonSerialize();

        $expectedFields = ['id', 'title', 'description', 'startDate', 'endDate', 'userId'];
        foreach ($expectedFields as $field) {
            self::assertArrayHasKey($field, $json, "Missing field: $field");
        }

        self::assertCount(6, $json, 'Event should have exactly 6 fields');

    }//end testJsonSerializeIncludesAllFields()

    /**
     * Test Event getJsonFields returns expected field list.
     *
     * @return void
     */
    public function testGetJsonFieldsReturnsExpectedFields(): void
    {
        $event  = new Event();
        $fields = $event->getJsonFields();

        self::assertSame(['id', 'title', 'description', 'startDate', 'endDate', 'userId'], $fields);

    }//end testGetJsonFieldsReturnsExpectedFields()

    /**
     * Test Event hydration from array data.
     *
     * @return void
     */
    public function testHydrateFromArray(): void
    {
        $event = new Event();
        $event->hydrate([
            'title' => 'Winter Gathering',
            'description' => 'A cold event',
            'userId' => 'admin',
        ]);

        $json = $event->jsonSerialize();

        self::assertSame('Winter Gathering', $json['title']);
        self::assertSame('A cold event', $json['description']);
        self::assertSame('admin', $json['userId']);

    }//end testHydrateFromArray()

    /**
     * Test Event has userId property for user-scoped events (EVP-011).
     *
     * @return void
     */
    public function testEventHasUserIdProperty(): void
    {
        $event = new Event();
        $event->setUserId('player1');

        $json = $event->jsonSerialize();

        self::assertSame('player1', $json['userId']);

    }//end testEventHasUserIdProperty()

    /**
     * Test toArray returns same data as jsonSerialize.
     *
     * @return void
     */
    public function testToArrayMatchesJsonSerialize(): void
    {
        $event = new Event();
        $event->setTitle('Test');

        self::assertSame($event->jsonSerialize(), $event->toArray());

    }//end testToArrayMatchesJsonSerialize()

    /**
     * Test Event default values are null.
     *
     * @return void
     */
    public function testDefaultValuesAreNull(): void
    {
        $event = new Event();
        $json  = $event->jsonSerialize();

        self::assertNull($json['title']);
        self::assertNull($json['description']);
        self::assertNull($json['startDate']);
        self::assertNull($json['endDate']);
        self::assertNull($json['userId']);

    }//end testDefaultValuesAreNull()

}//end class
