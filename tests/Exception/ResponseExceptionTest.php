<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Exception;

use Kyto\Alibaba\Exception\ResponseException;
use PHPUnit\Framework\TestCase;

class ResponseExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $endpoint = '/example/test/get';
        $type = 'SYSTEM';
        $message = 'Error happened please fix';
        $errorCode = 'ErrorHappened';
        $requestId = '2101d05f17144750947504007';
        $traceId = '21032cac17144750947448194e339b';
        $previous = new \RuntimeException('Previous');

        $exception = new ResponseException($endpoint, $type, $message, $errorCode, $requestId, $traceId, $previous);

        $expectedMessage = '[ErrorHappened] Error happened please fix.'
            . ' Endpoint: "/example/test/get".'
            . ' Request id: "2101d05f17144750947504007".'
            . ' Trace id: "21032cac17144750947448194e339b".';
        self::assertSame($expectedMessage, $exception->getMessage());

        self::assertSame($type, $exception->getType());
        self::assertSame($errorCode, $exception->getErrorCode());
        self::assertSame($requestId, $exception->getRequestId());
        self::assertSame($traceId, $exception->getTraceId());
        self::assertSame($previous, $exception->getPrevious());
    }
}
