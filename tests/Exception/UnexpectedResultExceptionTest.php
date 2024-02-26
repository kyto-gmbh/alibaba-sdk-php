<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Exception;

use Kyto\Alibaba\Exception\UnexpectedResultException;
use PHPUnit\Framework\TestCase;

class UnexpectedResultExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $message = 'Message';
        $code = 1;
        $previous = new \RuntimeException('Previous');

        $exception = new UnexpectedResultException($message, $code, $previous);

        self::assertSame('Message', $exception->getMessage());
        self::assertSame($code, $exception->getCode());
        self::assertSame($previous, $exception->getPrevious());
    }
}
