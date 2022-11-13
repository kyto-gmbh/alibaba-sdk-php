<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Util;

class Formatter
{
    public static function getAsArrayOfString(array $array): array
    {
        return array_map(static fn($item) => (string) $item, $array);
    }
}
