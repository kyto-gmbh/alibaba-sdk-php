<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Endpoint;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Exception\ResponseException;
use Kyto\Alibaba\Exception\UnexpectedResultException;
use Kyto\Alibaba\Factory\CategoryFactory;
use Kyto\Alibaba\Model\Category;
use Kyto\Alibaba\Model\CategoryAttribute;
use Kyto\Alibaba\Model\CategoryLevelAttribute;

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
     * @throws ResponseException
     */
    public function get(?string $id = null): Category
    {
        $id = $id ?? '0'; // '0' to fetch root categories

        $data = $this->client->request([
            'method' => 'alibaba.icbu.category.get.new',
            'cat_id' => $id
        ]);

        return $this->categoryFactory->createCategory($data);
    }

    /**
     * Get system-defined attributes based on category ID
     * @link https://developer.alibaba.com/en/doc.htm?spm=a219a.7629140.0.0.188675fe5JPvEa#?docType=2&docId=25348
     *
     * @return CategoryAttribute[]
     * @throws ResponseException
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

    /**
     * Get next-level attribute based on category, attribute and optionally level attribute value ID.
     * @link https://developer.alibaba.com/en/doc.htm?spm=a2728.12183079.k2mwm9fd.1.4b3630901WuQWY#?docType=2&docId=48659
     *
     * @param ?string $valueId provide null to fetch root level
     * @throws ResponseException|UnexpectedResultException
     */
    public function getLevelAttribute(
        string $categoryId,
        string $attributeId,
        ?string $valueId = null
    ): CategoryLevelAttribute {
        $attributeValueRequest = [
            'cat_id' => $categoryId,
            'attr_id' => $attributeId,
            'value_id' => $valueId ?? '0'
        ];

        $data = $this->client->request([
            'method' => 'alibaba.icbu.category.level.attr.get',
            'attribute_value_request' => json_encode($attributeValueRequest)
        ]);

        $errorMessage = sprintf(
            'Result list for category id: "%s", attribute id: "%s", value id: "%s" is empty.',
            $categoryId,
            $attributeId,
            $valueId
        );

        $attribute = $data['alibaba_icbu_category_level_attr_get_response']['result_list']
            ?? throw new UnexpectedResultException($errorMessage);

        return $this->categoryFactory->createLevelAttribute($attribute);
    }
}
