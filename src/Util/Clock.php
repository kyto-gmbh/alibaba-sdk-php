<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Util;

class Clock
{
    public function now(?string $timezone = null): \DateTime
    {
        if ($timezone === null) {
            return new \DateTime();
        }

        return new \DateTime('now', new \DateTimeZone($timezone));
    }
}
