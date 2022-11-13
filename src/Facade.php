<?php

declare(strict_types=1);

namespace Kyto\Alibaba;

use Kyto\Alibaba\Endpoint\CategoryEndpoint;
use Kyto\Alibaba\Util\Clock;
use Symfony\Component\HttpClient\HttpClient;

class Facade
{
    public CategoryEndpoint $category;

    /**
     * Facade factory method. Use this to create new Alibaba SDK interaction object.
     *
     * @param string $apiKey Also referenced as "app key" in the Alibaba docs
     * @param string $secret Also referenced as "app security" in the Alibaba docs
     */
    public static function create(string $apiKey, string $secret): self
    {
        return new self(
            $apiKey,
            new Client($apiKey, $secret, HttpClient::create(), new Clock()),
        );
    }

    /**
     * @internal
     */
    public function __construct(
        private string $apiKey,
        private Client $client,
    ) {
        $this->category = CategoryEndpoint::create($this->client);
    }

    /**
     * Making GET request to this URL will ask to login to Alibaba and authorize this API key to have access
     * to the account. In other words client should visit this url and authorize App to access Alibaba account by API.
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
}
