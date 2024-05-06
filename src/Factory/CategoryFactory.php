<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Factory;

use Kyto\Alibaba\Util\Formatter;
use Kyto\Alibaba\Model\Category;

/**
 * @internal
 */
class CategoryFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public function createCategory(array $data): Category
    {
        $category = $data['result']['result'];

        $model = new Category();
        $model->id = (string) $category['category_id'];
        $model->name = (string) $category['name'];
        $model->nameCN = (string) ($category['cn_name'] ?? '');
        $model->level = (int) $category['level'];
        $model->isLeaf = (bool) $category['leaf_category'];
        $model->parents = Formatter::getAsArrayOfString($category['parent_ids'] ?? []);
        $model->children = Formatter::getAsArrayOfString($category['child_ids'] ?? []);

        return $model;
    }
}
