<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

/**
 * The actual request needs it as a json, so use json_encode() on this model before you use it in a request.
 */
class CategoryLevelAttributeRequest implements \JsonSerializable
{
    public string $categoryId;
    public string $attributeId;
    public string $valueId;

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'cat_id' => $this->categoryId,
            'attr_id' => $this->attributeId,
            'value_id' => $this->valueId
        ];
    }
}
