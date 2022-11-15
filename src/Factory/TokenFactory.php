<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Factory;

use Kyto\Alibaba\Model\Token;

class TokenFactory
{
    /**
     * @param mixed[] $data
     */
    public function createToken(array $data): Token
    {
        $jsonResult = $data['top_auth_token_create_response']['token_result'];
        $token = json_decode($jsonResult, true, 512, JSON_THROW_ON_ERROR);

        $model = new Token();
        $model->userId = (string) $token['user_id'];
        $model->userName = $token['user_nick'] ?? null;

        $model->token = (string) $token['access_token'];
        $model->tokenExpireAt = $this->getMillisecondsAsDateTime((int) $token['expire_time']);

        $model->refreshToken = (string) $token['refresh_token'];
        $model->refreshTokenExpireAt = $this->getMillisecondsAsDateTime((int) $token['refresh_token_valid_time']);

        return $model;
    }

    /**
     * @param int $milliseconds Alibaba provides Unix time in milliseconds
     */
    private function getMillisecondsAsDateTime(int $milliseconds): \DateTimeImmutable
    {
        $value = (string) ($milliseconds / 1000);
        $datetime = \DateTimeImmutable::createFromFormat('U.u', $value);
        if ($datetime === false) {
            throw new \UnexpectedValueException(sprintf('Unable to parse "%s" as microtime.', $milliseconds));
        }
        return $datetime;
    }
}
