<?php

declare(strict_types=1);

namespace Kyto\Alibaba;

use Kyto\Alibaba\Model\Category;
use Symfony\Component\HttpClient\HttpClient;

class Facade
{
    /**
     * @param string $apiKey Also referenced as "app key" in the Alibaba docs
     * @param string $secret Also referenced as "app security" in the Alibaba docs
     */
    public static function create(string $apiKey, string $secret): self
    {
        return new self(
            $apiKey,
            new Client($apiKey, $secret, HttpClient::create()),
        );
    }

    public function __construct(
        private string $apiKey,
        private Client $client,
    ) {
    }

    /**
     * Making GET request to this URL will ask to login to Alibaba and authorize this API key to have access to the account.
     * In other words client should visit this url and authorize App to access Alibaba account by API.
     *
     * @param string $callbackUrl URL where authorization code returned. Via method GET in "code" parameter.
     */
    public function getAuthorizationUrl(string $callbackUrl): string
    {
        return 'https://oauth.alibaba.com/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $this->apiKey,
            'redirect_uri' => $callbackUrl,
            'State' => '1212',
            'view' => 'web',
            'sp' => 'ICBU',
        ]);
    }

    /**
     * @param string $id Provide '0' to fetch root categories
     */
    public function getCategory(string $id): Category
    {
        $data = $this->client->request([
            'method' => 'alibaba.icbu.category.get.new',
            'cat_id' => $id,
        ]);

        // TODO: validate response

        $categoryData = $data['alibaba_icbu_category_get_new_response']['category'];

        $model = new Category();
        $model->id = (string) $categoryData['category_id'];
        $model->name = (string) $categoryData['name'];
        $model->nameCN = (string) ($categoryData['cn_name'] ?? '');
        $model->level = (int) $categoryData['level'];
        $model->isLeaf = (bool) $categoryData['leaf_category'];
        $model->parentIds = array_map(static fn($item) => (string) $item, $categoryData['parent_ids']['number'] ?? []);
        $model->childIds = array_map(static fn($item) => (string) $item, $categoryData['child_ids']['number'] ?? []);

        return $model;
    }
}
