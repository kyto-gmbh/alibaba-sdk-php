<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

class CategoryAttributeValue
{
    public string $id;
    public string $name;
    public bool $isSku;

    /** @var string[] */
    public array $childAttributeIds = [];
}
