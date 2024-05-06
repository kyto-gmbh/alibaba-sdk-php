<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Endpoint;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Endpoint\TokenEndpoint;
use Kyto\Alibaba\Factory\TokenFactory;
use Kyto\Alibaba\Model\Token;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TokenEndpointTest extends TestCase
{
    private MockObject $client;
    private MockObject $tokenFactory;
    private TokenEndpoint $tokenEndpoint;

    public function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->tokenFactory = $this->createMock(TokenFactory::class);
        $this->tokenEndpoint = new TokenEndpoint($this->client, $this->tokenFactory);
    }

    public function tearDown(): void
    {
        unset(
            $this->client,
            $this->tokenFactory,
            $this->tokenEndpoint,
        );
    }

    public function testCreate(): void
    {
        $actual = TokenEndpoint::create($this->createMock(Client::class));
        self::assertInstanceOf(TokenEndpoint::class, $actual);
    }

    public function testNew(): void
    {
        $authorizationCode = 'auth-code';
        $data = ['response' => 'data'];

        $this->client
            ->expects(self::once())
            ->method('request')
            ->with(
                '/auth/token/create',
                [
                    'code' => $authorizationCode,
                ]
            )
            ->willReturn($data);

        $token = new Token();

        $this->tokenFactory
            ->expects(self::once())
            ->method('createToken')
            ->with($data)
            ->willReturn($token);

        $actual = $this->tokenEndpoint->new($authorizationCode);
        self::assertSame($token, $actual);
    }

    public function testRefresh(): void
    {
        $refreshToken = 'refresh-token';
        $data = ['response' => 'data', 'account' => 'user@example.com'];

        $token = new Token();
        $token->account = 'user@example.com';
        $token->refreshToken = $refreshToken;

        $this->client
            ->expects(self::once())
            ->method('request')
            ->with(
                '/auth/token/refresh',
                [
                    'refresh_token' => $refreshToken,
                ]
            )
            ->willReturn($data);

        $expected = new Token();

        $this->tokenFactory
            ->expects(self::once())
            ->method('createToken')
            ->with($data)
            ->willReturn($expected);

        $actual = $this->tokenEndpoint->refresh($token);
        self::assertSame($expected, $actual);
    }
}
