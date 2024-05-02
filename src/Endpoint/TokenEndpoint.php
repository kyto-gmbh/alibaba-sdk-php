<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Endpoint;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Exception\ResponseException;
use Kyto\Alibaba\Factory\TokenFactory;
use Kyto\Alibaba\Model\Token;
use Kyto\Alibaba\Util\Clock;

class TokenEndpoint
{
    /**
     * @internal
     */
    public static function create(Client $client): self
    {
        return new self($client, new TokenFactory(new Clock()));
    }

    /**
     * @internal
     */
    public function __construct(
        private Client $client,
        private TokenFactory $tokenFactory,
    ) {
    }

    /**
     * To obtain authorization code see corresponding facade method.
     * @link https://openapi.alibaba.com/doc/api.htm?spm=a2o9m.11193531.0.0.2fabf453xGO6n7#/api?cid=4&path=/auth/token/create&methodType=GET/POST
     * @see \Kyto\Alibaba\Facade::getAuthorizationUrl
     *
     * @throws ResponseException|\JsonException
     */
    public function new(string $authorizationCode): Token
    {
        $data = $this->client->request('/auth/token/create', [
            'code' => $authorizationCode,
        ]);

        return $this->tokenFactory->createToken($data);
    }
}
