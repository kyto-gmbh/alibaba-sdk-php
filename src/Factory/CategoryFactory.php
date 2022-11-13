<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Factory;

use Kyto\Alibaba\Util\Formatter;
use Kyto\Alibaba\Model\Category;
use Kyto\Alibaba\Model\CategoryAttribute;
use Kyto\Alibaba\Model\CategoryAttributeValue;

/**
 * @internal
 */
class CategoryFactory
{
    /**
     * @param mixed[] $data
     */
    public function createCategory(array $data): Category
    {
        $category = $data['alibaba_icbu_category_get_new_response']['category'];

        $model = new Category();
        $model->id = (string) $category['category_id'];
        $model->name = (string) $category['name'];
        $model->nameCN = (string) ($category['cn_name'] ?? '');
        $model->level = (int) $category['level'];
        $model->isLeaf = (bool) $category['leaf_category'];
        $model->parents = Formatter::getAsArrayOfString($category['parent_ids']['number'] ?? []);
        $model->children = Formatter::getAsArrayOfString($category['child_ids']['number'] ?? []);

        return $model;
    }

    /**
     * @param mixed[] $data
     */
    public function createAttribute(array $data): CategoryAttribute
    {
        $model = new CategoryAttribute();

        $model->id = (string) $data['attr_id'];
        $model->name = (string) $data['en_name'];
        $model->isRequired = (bool) $data['required'];

        $model->inputType = (string) $data['input_type'];
        $model->showType = (string) $data['show_type'];
        $model->valueType = (string) $data['value_type'];

        $model->isSku = (bool) $data['sku_attribute'];
        $model->hasCustomizeImage = (bool) $data['customize_image'];
        $model->hasCustomizeValue = (bool) $data['customize_value'];
        $model->isCarModel = (bool) $data['car_model'];

        $model->units = Formatter::getAsArrayOfString($data['units']['string'] ?? []);

        $values = $data['attribute_values']['attribute_value'] ?? [];
        foreach ($values as $value) {
            $model->values[] = $this->createAttributeValue($value);
        }

        return $model;
    }

    /**
     * @param mixed[] $data
     */
    public function createAttributeValue(array $data): CategoryAttributeValue
    {
        $model = new CategoryAttributeValue();

        $model->id = (string) $data['attr_value_id'];
        $model->name = (string) $data['en_name'];
        $model->isSku = (bool) $data['sku_value'];
        $model->childAttributes = Formatter::getAsArrayOfString($data['child_attrs']['number'] ?? []);

        return $model;
    }
}
