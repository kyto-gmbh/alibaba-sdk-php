<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

use Kyto\Alibaba\Helper\Formatter;

class Category
{
    public string $id;
    public string $name;
    public string $nameCN;
    public int $level;
    public bool $isLeaf;

    /** @var string[] */
    public array $parentIds = [];

    /** @var string[] */
    public array $childIds = [];

    /**
     * @param mixed $data
     */
    public static function createFromRawData(array $data): self
    {
        $category = $data['alibaba_icbu_category_get_new_response']['category'];

        $self = new self();
        $self->id = (string) $category['category_id'];
        $self->name = (string) $category['name'];
        $self->nameCN = (string) ($category['cn_name'] ?? '');
        $self->level = (int) $category['level'];
        $self->isLeaf = (bool) $category['leaf_category'];
        $self->parentIds = Formatter::getArrayOfString($category['parent_ids']['number'] ?? []);
        $self->childIds = Formatter::getArrayOfString($category['child_ids']['number'] ?? []);

        return $self;
    }
}
