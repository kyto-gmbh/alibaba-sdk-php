<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

class CategoryAttribute
{
    public string $id;
    public string $name;
    public bool $isRequired;

    public string $inputType;
    public string $showType;
    public string $valueType;

    public bool $isSku;
    public bool $hasCustomizeImage;
    public bool $hasCustomizeValue;
    public bool $isCarModel;

    /** @var string[] */
    public array $units = [];

    /** @var CategoryAttributeValue[] */
    public array $values = [];
}
