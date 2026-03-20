<?php
declare(strict_types=1);
namespace OCA\LarpingApp\Tests\Unit\Db;

use OCA\LarpingApp\Db\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testJsonSerializeIncludesRequiredFields(): void
    {
        $event = new Event();
        $event->setTitle('Summer LARP 2025');
        $event->setDescription('Annual summer event');

        $json = $event->jsonSerialize();

        self::assertArrayHasKey('id', $json);
        self::assertArrayHasKey('title', $json);
        self::assertArrayHasKey('description', $json);
        self::assertSame('Summer LARP 2025', $json['title']);
    }

    public function testEventUsesTitle(): void
    {
        // Event uses 'title' instead of 'name' unlike other entities.
        $event = new Event();
        $fields = $event->getJsonFields();

        self::assertContains('title', $fields);
        self::assertNotContains('name', $fields);
    }

    public function testEventHasDateFields(): void
    {
        $event = new Event();
        $fields = $event->getJsonFields();

        self::assertContains('startDate', $fields);
        self::assertContains('endDate', $fields);
    }

    public function testEventHasUserIdField(): void
    {
        $event = new Event();
        $fields = $event->getJsonFields();

        self::assertContains('userId', $fields);
    }

    public function testInternalEventLacksEffectsField(): void
    {
        $event = new Event();
        $fields = $event->getJsonFields();

        // Internal entity does not have effects array.
        self::assertNotContains('effects', $fields);
    }

    public function testToArrayReturnsJsonSerialize(): void
    {
        $event = new Event();
        $event->setTitle('Test');

        self::assertSame($event->jsonSerialize(), $event->toArray());
    }
}
