<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Factory;

use Kyto\Alibaba\Factory\TokenFactory;
use Kyto\Alibaba\Model\Token;
use PHPUnit\Framework\TestCase;

class TokenFactoryTest extends TestCase
{
    private TokenFactory $tokenFactory;

    public function setUp(): void
    {
        $this->tokenFactory = new TokenFactory();
    }

    public function tearDown(): void
    {
        unset(
            $this->tokenFactory,
        );
    }

    /**
     * @dataProvider createTokenDataProvider
     * @param mixed[] $data
     */
    public function testCreateToken(array $data, Token $expected): void
    {
        $actual = $this->tokenFactory->createToken($data);
        self::assertEquals($expected, $actual);
    }

    /**
     * @return mixed[]
     */
    public function createTokenDataProvider(): iterable
    {
        // Full result
        $data = [
            'top_auth_token_create_response' => [
                'token_result' => json_encode([
                    'user_id' => '123',
                    'user_nick' => 'example',
                    'access_token' => 'access-token',
                    'expire_time' => 1468663236386,
                    'refresh_token' => 'refresh-token',
                    'refresh_token_valid_time' => 1469643536337,
                ], JSON_THROW_ON_ERROR),
            ]
        ];

        $expected = new Token();
        $expected->userId = '123';
        $expected->userName = 'example';
        $expected->token = 'access-token';
        $expected->tokenExpireAt = (new \DateTimeImmutable())->setDate(2016, 7, 16)->setTime(10, 0, 36, 386000);
        $expected->refreshToken = 'refresh-token';
        $expected->refreshTokenExpireAt = (new \DateTimeImmutable())->setDate(2016, 7, 27)->setTime(18, 18, 56, 337000);

        yield [$data, $expected];

        // No username
        $data = [
            'top_auth_token_create_response' => [
                'token_result' => json_encode([
                    'user_id' => '123',
                    'access_token' => 'access-token',
                    'expire_time' => 1468663236386,
                    'refresh_token' => 'refresh-token',
                    'refresh_token_valid_time' => 1469643536337,
                ], JSON_THROW_ON_ERROR),
            ]
        ];

        $expected = new Token();
        $expected->userId = '123';
        $expected->userName = null;
        $expected->token = 'access-token';
        $expected->tokenExpireAt = (new \DateTimeImmutable())->setDate(2016, 7, 16)->setTime(10, 0, 36, 386000);
        $expected->refreshToken = 'refresh-token';
        $expected->refreshTokenExpireAt = (new \DateTimeImmutable())->setDate(2016, 7, 27)->setTime(18, 18, 56, 337000);

        yield [$data, $expected];
    }
}
