<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Factory;

use Kyto\Alibaba\Model\Token;
use Kyto\Alibaba\Util\Clock;

/**
 * @internal
 */
class TokenFactory
{
    public function __construct(
        private Clock $clock,
    ) {
    }

    /**
     * @param mixed[] $data
     */
    public function createToken(array $data): Token
    {
        $baseDatetime = $this->clock->now();

        $model = new Token();
        $model->account = (string) $data['account'];

        $model->token = (string) $data['access_token'];
        $model->tokenExpireAt = $this->getExpiresInAsDateTime($baseDatetime, (int) $data['expires_in']);

        $model->refreshToken = (string) $data['refresh_token'];
        $model->refreshTokenExpireAt = $this->getExpiresInAsDateTime($baseDatetime, (int) $data['refresh_expires_in']);

        return $model;
    }

    /**
     * It is recommended by the Alibaba API docs to refresh the token 30 minutes before it expires.
     * @link https://openapi.alibaba.com/doc/doc.htm?spm=a2o9m.11223882.0.0.1566722cTOuz7W#/?docId=56
     */
    private function getExpiresInAsDateTime(\DateTime $baseDatetime, int $expiresIn): \DateTimeImmutable
    {
        $recommendedExpire = (int) ($expiresIn - (30 * 60)); // 30 minutes before actual expiration
        $expiresIn = $recommendedExpire > 0 ? $recommendedExpire : $expiresIn;

        $modifier = sprintf('+%d seconds', $expiresIn);
        $datetime = (clone $baseDatetime)->modify($modifier);
        return \DateTimeImmutable::createFromInterface($datetime);
    }
}
