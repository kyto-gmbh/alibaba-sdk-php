<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Endpoint;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Exception\ResponseException;
use Kyto\Alibaba\Factory\CategoryFactory;
use Kyto\Alibaba\Model\Category;
use Kyto\Alibaba\Model\Token;

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
     * @link https://openapi.alibaba.com/doc/api.htm?spm=a2o9m.11223882.0.0.1566722cTOuz7W#/api?cid=1&path=/icbu/product/category/get&methodType=GET/POST
     *
     * @param ?string $id Provide `null` to fetch root categories
     * @throws ResponseException
     */
    public function get(Token $token, ?string $id = null): Category
    {
        $id = $id ?? '0'; // '0' to fetch root categories

        $data = $this->client->request('/icbu/product/category/get', [
            'access_token' => $token->token,
            'cat_id' => $id,
        ]);

        return $this->categoryFactory->createCategory($data);
    }
}
