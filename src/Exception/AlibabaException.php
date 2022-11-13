<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Exception;

/**
 * @internal
 */
class AlibabaException extends \RuntimeException
{
    /**
     * @param mixed[] $errorResponse
     */
    public function __construct(array $errorResponse, ?\Throwable $previous = null)
    {
        $message = sprintf(
            '%s. Sub-code: "%s". Sub-message: "%s".',
            $errorResponse['msg'],
            $errorResponse['sub_code'],
            $errorResponse['sub_msg'],
        );
        parent::__construct($message, (int) $errorResponse['code'], $previous);
    }
}
