<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Endpoint;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Factory\ProductFactory;
use Kyto\Alibaba\Model\Category;
use Kyto\Alibaba\Model\Token;

class ProductEndpoint
{
    /**
     * @internal
     */
    public static function create(Client $client): self
    {
        return new self($client, new ProductFactory());
    }

    /**
     * @internal
     */
    public function __construct(
        private Client $client,
        private ProductFactory $productFactory, // @phpstan-ignore-line
    ) {
    }

    /**
     * Obtain the page rules and fill-in fields for new product release.
     * @link https://openapi.alibaba.com/doc/api.htm?spm=a2o9m.11193531.0.0.2fabf453CIh7hC#/api?cid=1&path=/alibaba/icbu/product/schema/get&methodType=GET/POST
     *
     * @param string $language Allowed values are not documented in the API docs.
     *                         Seems like uses the same locale codes as on Alibaba website:
     *                         en_US, es_ES, fr_FR, it_IT, de_DE, pt_PT, ru_RU, ja_JP,
     *                         ar_SA, ko_KR, tr_TR, vi_VN, th_TH, id_ID, he_IL, hi_IN, zh_CN
     * @return array<mixed>
     */
    public function getSchema(Token $token, Category $category, string $language = 'en_US'): array
    {
        $data = $this->client->request('/alibaba/icbu/product/schema/get', [
            'cat_id' => $category->id,
            'access_token' => $token->token,
            'language' => $language,
        ]);

        return $data; // TODO: Add normalized model for response
    }
}
