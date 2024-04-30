<?php

declare(strict_types=1);

namespace Kyto\Alibaba;

use Kyto\Alibaba\Endpoint\CategoryEndpoint;
use Kyto\Alibaba\Endpoint\ProductEndpoint;
use Kyto\Alibaba\Endpoint\TokenEndpoint;
use Kyto\Alibaba\Util\Clock;
use Symfony\Component\HttpClient\HttpClient;

class Facade
{
    public CategoryEndpoint $category;
    public ProductEndpoint $product;
    public TokenEndpoint $token;

    /**
     * Facade factory method. Use this to create new Alibaba SDK interaction object.
     *
     * @param string $key Also referenced as "AppKey" in the Alibaba docs
     * @param string $secret Also referenced as "App Secret" in the Alibaba docs
     */
    public static function create(string $key, string $secret): self
    {
        return new self(
            $key,
            new Client($key, $secret, HttpClient::create(), new Clock()),
        );
    }

    /**
     * @internal
     */
    public function __construct(
        private string $key,
        private Client $client,
    ) {
        $this->category = CategoryEndpoint::create($this->client);
        $this->product = ProductEndpoint::create($this->client);
        $this->token = TokenEndpoint::create($this->client);
    }

    /**
     * Making GET request to this URL will ask to login to Alibaba and authorize this API key to have access
     * to the account. In other words client should visit this url and authorize App to access Alibaba account by API.
     * @link https://openapi.alibaba.com/doc/doc.htm?spm=a2o9m.11193494.0.0.50dd3a3armsNgS#/?docId=56
     *
     * @param string $callbackUrl URL where authorization code returned. Via method GET in "code" parameter. Should be
     *                            the same as in the App settings in Alibaba.
     */
    public function getAuthorizationUrl(string $callbackUrl): string
    {
        return 'https://openapi-auth.alibaba.com/oauth/authorize?' . http_build_query([
            'response_type' => 'code',
            'redirect_uri' => $callbackUrl,
            'client_id' => $this->key,
        ]);
    }
}
