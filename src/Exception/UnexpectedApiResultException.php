<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Exception;

class UnexpectedApiResultException extends AlibabaException
{
    /**
     * @internal
     */
    public function __construct(
        string $message,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
