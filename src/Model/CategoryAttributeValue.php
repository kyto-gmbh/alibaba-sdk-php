<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

use Kyto\Alibaba\Helper\Formatter;

class CategoryAttributeValue
{
    public string $id;
    public string $name;
    public bool $isSku;

    /** @var string[] */
    public array $childAttributes = [];

    /**
     * @internal
     * @param mixed $data
     */
    public static function createFromRawData(array $data): self
    {
        $self = new self();

        $self->id = (string) $data['attr_value_id'];
        $self->name = (string) $data['en_name'];
        $self->isSku = (bool) $data['sku_value'];
        $self->childAttributes = Formatter::getArrayOfString($data['child_attrs']['number'] ?? []);

        return $self;
    }
}
