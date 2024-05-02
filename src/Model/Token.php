<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

class Token
{
    public string $account;

    public string $token;
    public \DateTimeImmutable $tokenExpireAt;

    public string $refreshToken;
    public \DateTimeImmutable $refreshTokenExpireAt;
}
