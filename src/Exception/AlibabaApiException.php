<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Exception;

class AlibabaApiException extends AlibabaException
{
    /**
     * @internal
     */
    public function __construct(
        string $message,
        int $code,
        private string $subMessage,
        private string $subCode,
        ?\Throwable $previous = null
    ) {
        $message = sprintf('%s. Sub-code: "%s". Sub-message: "%s".', $message, $this->subCode, $this->subMessage);
        parent::__construct($message, $code, $previous);
    }

    public function getSubMessage(): string
    {
        return $this->subMessage;
    }

    public function getSubCode(): string
    {
        return $this->subCode;
    }
}
