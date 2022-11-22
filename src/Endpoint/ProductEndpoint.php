<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Endpoint;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Factory\ProductFactory;
use Kyto\Alibaba\Model\ProductGroup;
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
        private ProductFactory $productFactory,
    ) {
    }

    /**
     * Get product group information.
     * @link https://developer.alibaba.com/en/doc.htm?spm=a219a.7629140.0.0.188675fe5JPvEa#?docType=2&docId=25299
     *
     * @param ?string $id Provide `null` to fetch root groups
     */
    public function getGroup(Token $token, ?string $id = null): ProductGroup
    {
        $id = $id ?? '-1'; // '-1' to fetch root groups

        $data = $this->client->request([
            'method' => 'alibaba.icbu.product.group.get',
            'session' => $token->token,
            'group_id' => $id
        ]);

        return $this->productFactory->createGroup($data);
    }
}
