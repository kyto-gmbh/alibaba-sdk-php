<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Endpoint;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Exception\ResponseException;
use Kyto\Alibaba\Factory\TokenFactory;
use Kyto\Alibaba\Model\Token;

class TokenEndpoint
{
    /**
     * @internal
     */
    public static function create(Client $client): self
    {
        return new self($client, new TokenFactory());
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
     * @link https://open.taobao.com/api.htm?spm=a219a.7386653.0.0.41449b714zR8KI&docId=25388&docType=2&source=search
     * @see \Kyto\Alibaba\Facade::getAuthorizationUrl
     *
     * @throws ResponseException|\JsonException
     */
    public function new(string $authorizationCode): Token
    {
        $data = $this->client->request([
            'method' => 'taobao.top.auth.token.create',
            'code' => $authorizationCode,
        ]);

        return $this->tokenFactory->createToken($data);
    }
}
