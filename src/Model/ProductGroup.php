<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

class ProductGroup
{
    public string $id;
    public string $name;
    public ?string $parent;

    /** @var string[] */
    public array $children = [];
}
