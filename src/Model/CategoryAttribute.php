<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

use Kyto\Alibaba\Helper\Formatter;

class CategoryAttribute
{
    public string $id;
    public string $name;
    public bool $isRequired;

    public string $inputType; // Known values: single_select, multi_select, input
    public string $showType; // Known values: list_box (single_select), check_box (multi_select), input (input)
    public string $valueType; // Known values: string, number

    public bool $isSku;
    public bool $hasCustomizeImage;
    public bool $hasCustomizeValue;
    public bool $isCarModel;

    /** @var string[] */
    public array $units = [];

    /** @var CategoryAttributeValue[] */
    public array $values = [];

    /**
     * @param mixed $data
     */
    public static function createFromRawData(array $data): self
    {
        $self = new self();

        $self->id = (string) $data['attr_id'];
        $self->name = (string) $data['en_name'];
        $self->isRequired = (bool) $data['required'];

        $self->inputType = (string) $data['input_type'];
        $self->showType = (string) $data['show_type'];
        $self->valueType = (string) $data['value_type'];

        $self->isSku = (bool) $data['sku_attribute'];
        $self->hasCustomizeImage = (bool) $data['customize_image'];
        $self->hasCustomizeValue = (bool) $data['customize_value'];
        $self->isCarModel = (bool) $data['car_model'];

        $self->units = Formatter::getArrayOfString($data['units']['string'] ?? []);

        $values = $data['attribute_values']['attribute_value'] ?? [];
        foreach ($values as $value) {
            $self->values[] = CategoryAttributeValue::createFromRawData($value);
        }

        return $self;
    }
}
