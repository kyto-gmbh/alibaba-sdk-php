<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Exception;

use Kyto\Alibaba\Exception\AlibabaException;
use PHPUnit\Framework\TestCase;

class AlibabaExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $message = 'Message';
        $code = 1;
        $subMessage = 'Sub-message';
        $subCode = 'sub.code';
        $previous = new \RuntimeException('Previous');

        $exception = new AlibabaException($message, $code, $subMessage, $subCode, $previous);

        self::assertSame('Message. Sub-code: "sub.code". Sub-message: "Sub-message".', $exception->getMessage());
        self::assertSame($code, $exception->getCode());
        self::assertSame($subMessage, $exception->getSubMessage());
        self::assertSame($subCode, $exception->getSubCode());
        self::assertSame($previous, $exception->getPrevious());
    }
}
