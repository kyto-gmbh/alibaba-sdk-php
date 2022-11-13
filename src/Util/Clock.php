<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Util;

class Clock
{
    public function now(?string $timezone = null): \DateTime
    {
        return $timezone === null ? new \DateTime() : new \DateTime('now', new \DateTimeZone($timezone));
    }
}
