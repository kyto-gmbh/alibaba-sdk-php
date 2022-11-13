<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Exception;

/**
 * @internal
 */
class AlibabaException extends \RuntimeException
{
    public function __construct(
        string $message,
        int $code,
        string $subMessage,
        string $subCode,
        ?\Throwable $previous = null
    ) {
        $message = sprintf('%s. Sub-code: "%s". Sub-message: "%s".', $message, $subCode, $subMessage);
        parent::__construct($message, $code, $previous);
    }
}
