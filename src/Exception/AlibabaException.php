<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Exception;

class AlibabaException extends \RuntimeException
{
    /**
     * @internal
     */
    public function __construct(
        string $message,
        int $code,
        private ?string $subMessage,
        private ?string $subCode,
        ?\Throwable $previous = null
    ) {
        $subCodePart = $this->subCode !== null ? sprintf(' Sub-code: "%s".', $this->subCode) : null;
        $subMessagePart = $this->subMessage !== null ? sprintf(' Sub-message: "%s".', $this->subMessage) : null;
        $message = sprintf('%s.%s%s', $message, $subCodePart, $subMessagePart);

        parent::__construct($message, $code, $previous);
    }

    public function getSubMessage(): ?string
    {
        return $this->subMessage;
    }

    public function getSubCode(): ?string
    {
        return $this->subCode;
    }
}
