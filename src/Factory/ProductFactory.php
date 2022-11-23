<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Factory;

use Kyto\Alibaba\Model\ProductGroup;
use Kyto\Alibaba\Util\Formatter;

/**
 * @internal
 */
class ProductFactory
{
    /**
     * @param mixed[] $data
     */
    public function createGroup(array $data): ProductGroup
    {
        $group = $data['alibaba_icbu_product_group_get_response']['product_group'];

        $model = new ProductGroup();
        $model->id = (string) $group['group_id'];
        $model->name = (string) ($group['group_name'] ?? '');
        $model->parent = isset($group['parent_id']) ? (string) $group['parent_id'] : null;
        $model->children = Formatter::getAsArrayOfString($group['children_id_list']['number'] ?? []);

        return $model;
    }
}
