<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Model;

class Token
{
    public string $userId;
    public ?string $userName;

    public string $token;
    public \DateTimeImmutable $tokenExpireAt;

    public string $refreshToken;
    public \DateTimeImmutable $refreshTokenExpireAt;
}
