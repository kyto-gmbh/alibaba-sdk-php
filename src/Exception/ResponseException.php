<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Exception;

/**
 * Represents an error response from Alibaba API.
 * @link https://openapi.alibaba.com/doc/doc.htm?docId=19#/?docId=63
 */
class ResponseException extends AlibabaException
{
    /**
     * @internal
     */
    public function __construct(
        private string $type,
        string $message,
        private string $erorrCode,
        private string $requestId,
        private string $traceId,
        ?\Throwable $previous = null
    ) {
        $message = sprintf(
            '%s. %s. Request id: "%s". Trace id: "%s".',
            $this->erorrCode,
            $message,
            $this->requestId,
            $this->traceId,
        );
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return string Known values are:
     *                 - SYSTEM: API platform error
     *                 - ISV: Business data error
     *                 - ISP: Backend service error
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getErrorCode(): string
    {
        return $this->erorrCode;
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }

    public function getTraceId(): string
    {
        return $this->traceId;
    }
}
