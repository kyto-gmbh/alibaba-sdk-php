<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Util;

use Kyto\Alibaba\Util\Clock;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ClockTest extends TestCase
{
    private Clock $clock;

    public function setUp(): void
    {
        $this->clock = new Clock();
    }

    public function tearDown(): void
    {
        unset(
            $this->clock,
        );
    }

    #[DataProvider('nowDataProvider')]
    public function testNow(?string $timezoneName, ?\DateTimeZone $timezone): void
    {
        $actual = $this->clock->now($timezoneName);
        $expected = new \DateTime('now', $timezone);

        self::assertEquals(
            $expected->format('Y-m-d H:i e'),
            $actual->format('Y-m-d H:i e'),
        );
    }

    public static function nowDataProvider(): array
    {
        return [
            'default timezone' => [null, null],
            'timezone' => ['Atlantic/Azores', new \DateTimeZone('Atlantic/Azores')],
        ];
    }
}
