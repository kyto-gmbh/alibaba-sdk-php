<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Factory;

use Kyto\Alibaba\Enum\InputType;
use Kyto\Alibaba\Enum\ShowType;
use Kyto\Alibaba\Enum\ValueType;
use Kyto\Alibaba\Model\CategoryLevelAttribute;
use Kyto\Alibaba\Model\CategoryLevelAttributeValue;
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

    /**
     * @param array<string, mixed> $data
     */
    public function createAttribute(array $data): CategoryAttribute
    {
        $model = new CategoryAttribute();

        $model->id = (string) $data['attr_id'];
        $model->name = (string) $data['en_name'];
        $model->isRequired = (bool) $data['required'];

        $model->inputType = InputType::from($data['input_type']);
        $model->showType = ShowType::from($data['show_type']);
        $model->valueType = ValueType::from($data['value_type']);

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
     * @param array<string, mixed> $data
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

    /**
     * @param array<string, mixed> $data
     */
    public function createLevelAttribute(array $data): CategoryLevelAttribute
    {
        $model = new CategoryLevelAttribute();

        $model->id = (string) $data['property_id'];
        $model->name = (string) $data['property_en_name'];

        $model->values = [];
        $decodedValues = json_decode($data['values'], true);
        foreach ($decodedValues as $value) {
            $model->values[] = $this->createLevelAttributeValue($value);
        }

        return $model;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createLevelAttributeValue(array $data): CategoryLevelAttributeValue
    {
        $model = new CategoryLevelAttributeValue();
        $model->name = (string) $data['name'];
        $model->id = (string) $data['id'];
        $model->isLeaf = isset($data['leaf']);

        return $model;
    }
}
