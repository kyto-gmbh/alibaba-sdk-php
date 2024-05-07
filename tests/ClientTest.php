<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Exception\ResponseException;
use Kyto\Alibaba\Util\Clock;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ClientTest extends TestCase
{
    private const KEY = 'app-key';
    private const SECRET = 'app-secret';

    private MockObject $httpClient;
    private MockObject $clock;
    private Client $client;

    public function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->clock = $this->createMock(Clock::class);
        $this->client = new Client(self::KEY, self::SECRET, $this->httpClient, $this->clock);
    }

    public function tearDown(): void
    {
        unset(
            $this->httpClient,
            $this->clock,
            $this->client,
        );
    }

    #[DataProvider('requestDataProvider')]
    public function testRequest(bool $isSuccess, string $endpoint, array $responseData): void
    {
        $timestamp = '2024-04-30 18:17:25';
        $timezone = 'UTC';
        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $timestamp, new \DateTimeZone($timezone));

        $this->clock
            ->expects(self::once())
            ->method('now')
            ->with($timezone)
            ->willReturn($datetime);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')->willReturn($responseData);

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(
                'POST',
                'https://openapi-api.alibaba.com/rest/some/endpoint',
                [
                    'headers' => [
                        'User-Agent' => 'Kyto Alibaba Client',
                    ],
                    'body' => [
                        'app_key' => self::KEY,
                        'timestamp' => '1714501045000',
                        'hello' => 'world',
                        'test' => 'data',
                        'sign_method' => 'sha256',
                        'sign' => '99486884A406C07BC1EF420C886F8422B3FE18BD7420CF9CB65B82027430BF7C',
                    ]
                ]
            )
        ->willReturn($response);

        if (!$isSuccess) {
            $this->expectException(ResponseException::class);
        }

        $actual = $this->client->request($endpoint, ['hello' => 'world', 'test' => 'data']);
        self::assertSame($responseData, $actual);
    }

    public static function requestDataProvider(): array
    {
        return [
            'success' => [true, '/some/endpoint', ['successful' => 'response']],
            'success, no slash prefix endpoint' => [true, 'some/endpoint', ['successful' => 'response']],
            'error 1' => [false, '/some/endpoint', [
                'type' => 'ISP',
                'code' => 'ErrorHappened',
                'message' => 'Error happened please fix',
                'request_id' => '2101d05f17144750947504007',
                '_trace_id_' => '21032cac17144750947448194e339b'
            ]],
            'error 2' => [false, '/some/endpoint', [
                'result' => [
                    'success' => false,
                    'message_info' => 'Error happened please fix',
                    'msg_code' => 'isp.error-happened',
                ],
                'request_id' => '2101d05f17144750947504007',
                '_trace_id_' => '21032cac17144750947448194e339b'
            ]],
        ];
    }
}
