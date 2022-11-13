<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Endpoint;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Model\Category;
use Kyto\Alibaba\Model\CategoryAttribute;

class CategoryEndpoint
{
    /**
     * @internal
     */
    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * Get product listing category
     *
     * @param string $id Provide '0' to fetch root categories
     */
    public function get(string $id): Category
    {
        $data = $this->client->request([
            'method' => 'alibaba.icbu.category.get.new',
            'cat_id' => $id,
        ]);

        return Category::createFromRawData($data);
    }

    /**
     * Get system-defined attributes based on category ID
     *
     * @return CategoryAttribute[]
     */
    public function getAttributes(string $categotyId): array
    {
        $data = $this->client->request([
            'method' => 'alibaba.icbu.category.attribute.get',
            'cat_id' => $categotyId,
        ]);

        $result = [];

        $attributes = $data['alibaba_icbu_category_attribute_get_response']['attributes']['attribute'];
        foreach ($attributes as $attribute) {
            $result[] = CategoryAttribute::createFromRawData($attribute);
        }

        return $result;
    }
}
