<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Util;

use Kyto\Alibaba\Util\Formatter;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    public function testGetAsArrayOfString(): void
    {
        $actual = Formatter::getAsArrayOfString([
            1,
            'string',
            'int' => 12,
            'float' => 1.5,
            'key' => 'value',
            'bool' => true,
        ]);

        self::assertSame([
            '1',
            'string',
            'int' => '12',
            'float' => '1.5',
            'key' => 'value',
            'bool' => '1',
        ], $actual);
    }
}
