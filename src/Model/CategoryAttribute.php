<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

class CategoryAttribute
{
    public string $id;
    public string $name;
    public bool $isRequired;

    // TODO: change to enums once all values would be known
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
}
