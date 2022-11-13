<?php

declare(strict_types=1);

namespace Kyto\Alibaba;

use Kyto\Alibaba\Exception\AlibabaException;
use Kyto\Alibaba\Model\Category;
use Kyto\Alibaba\Model\CategoryAttribute;
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

        $this->throwOnError($data);

        return Category::createFromRawData($data);
    }

    /**
     * @return CategoryAttribute[]
     */
    public function getCategoryAttributes(string $categotyId): array
    {
        $data = $this->client->request([
            'method' => 'alibaba.icbu.category.attribute.get',
            'cat_id' => $categotyId,
        ]);

        $this->throwOnError($data);

        $result = [];

        $attributes = $data['alibaba_icbu_category_attribute_get_response']['attributes']['attribute'];
        foreach ($attributes as $attribute) {
            $result[] = CategoryAttribute::createFromRawData($attribute);
        }

        return $result;
    }

    /**
     * @param mixed[] $data
     */
    private function throwOnError(array $data): void
    {
        $errorResponse = $data['error_response'] ?? null;

        if ($errorResponse !== null) {
            throw new AlibabaException($errorResponse);
        }
    }
}
