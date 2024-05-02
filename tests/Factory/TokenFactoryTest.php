<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Factory;

use Kyto\Alibaba\Factory\TokenFactory;
use Kyto\Alibaba\Model\Token;
use Kyto\Alibaba\Util\Clock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TokenFactoryTest extends TestCase
{
    private Clock&MockObject $clock;
    private TokenFactory $tokenFactory;

    public function setUp(): void
    {
        $this->clock = $this->createMock(Clock::class);
        $this->tokenFactory = new TokenFactory($this->clock);
    }

    public function tearDown(): void
    {
        unset(
            $this->clock,
            $this->tokenFactory,
        );
    }

    public function testCreateToken(): void
    {
        $this->clock
            ->method('now')
            ->willReturn(
                new \DateTime('2016-07-16T10:00:00'),
            );

        $data = [
            'account' => 'user@example.com',
            'access_token' => 'access-token',
            'expires_in' => 120,
            'refresh_token' => 'refresh-token',
            'refresh_expires_in' => 7200,
        ];

        $expected = new Token();
        $expected->account = 'user@example.com';
        $expected->token = 'access-token';
        $expected->tokenExpireAt = (new \DateTimeImmutable())->setDate(2016, 7, 16)->setTime(10, 2, 0);
        $expected->refreshToken = 'refresh-token';
        $expected->refreshTokenExpireAt = (new \DateTimeImmutable())->setDate(2016, 7, 16)->setTime(11, 30, 0);

        $actual = $this->tokenFactory->createToken($data);
        self::assertEquals($expected, $actual);
    }
}
