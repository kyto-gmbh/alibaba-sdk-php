<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

use Kyto\Alibaba\Enum\InputType;
use Kyto\Alibaba\Enum\ShowType;
use Kyto\Alibaba\Enum\ValueType;

class CategoryAttribute
{
    public string $id;
    public string $name;
    public bool $isRequired;

    public InputType $inputType;
    public ShowType $showType;
    public ValueType $valueType;

    public bool $isSku;
    public bool $hasCustomizeImage;
    public bool $hasCustomizeValue;
    public bool $isCarModel;

    /** @var string[] */
    public array $units = [];

    /** @var CategoryAttributeValue[] */
    public array $values = [];
}
