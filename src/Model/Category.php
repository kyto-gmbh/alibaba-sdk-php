<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

class Category
{
    public string $id;
    public string $name;
    public string $nameCN;
    public int $level;
    public bool $isLeaf;

    /** @var string[] */
    public array $parents = [];

    /** @var string[] */
    public array $children = [];
}
