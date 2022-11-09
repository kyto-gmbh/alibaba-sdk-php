<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Helper;

class Formatter
{
    public static function getArrayOfString(array $array): array
    {
        return array_map(static fn($item) => (string) $item, $array);
    }
}
