<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Endpoint;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Factory\CategoryFactory;
use Kyto\Alibaba\Model\Category;
use Kyto\Alibaba\Model\CategoryAttribute;

class CategoryEndpoint
{
    /**
     * @internal
     */
    public static function create(Client $client): self
    {
        return new self($client, new CategoryFactory());
    }

    /**
     * @internal
     */
    public function __construct(
        private Client $client,
        private CategoryFactory $categoryFactory,
    ) {
    }

    /**
     * Get product listing category
     * @link https://developer.alibaba.com/en/doc.htm?spm=a219a.7629140.0.0.188675fe5JPvEa#?docType=2&docId=50064
     *
     * @param ?string $id Provide `null` to fetch root categories
     */
    public function get(?string $id = null): Category
    {
        $data = $this->client->request([
            'method' => 'alibaba.icbu.category.get.new',
            'cat_id' => $id ?? '0', // '0' to fetch root categories
        ]);

        return $this->categoryFactory->createCategory($data);
    }

    /**
     * Get system-defined attributes based on category ID
     * @link https://developer.alibaba.com/en/doc.htm?spm=a219a.7629140.0.0.188675fe5JPvEa#?docType=2&docId=25348
     *
     * @return CategoryAttribute[]
     */
    public function getAttributes(string $categoryId): array
    {
        $data = $this->client->request([
            'method' => 'alibaba.icbu.category.attribute.get',
            'cat_id' => $categoryId,
        ]);

        $result = [];

        $attributes = $data['alibaba_icbu_category_attribute_get_response']['attributes']['attribute'];
        foreach ($attributes as $attribute) {
            $result[] = $this->categoryFactory->createAttribute($attribute);
        }

        return $result;
    }
}
