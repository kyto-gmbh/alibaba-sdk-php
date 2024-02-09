<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

class CategoryLevelAttribute
{
    public string $id;
    public string $name;

    /** @var CategoryLevelAttributeValue[] */
    public array $values;
}
