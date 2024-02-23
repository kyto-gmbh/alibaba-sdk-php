<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Exception\AlibabaApiException;
use Kyto\Alibaba\Util\Clock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ClientTest extends TestCase
{
    private const API_KEY = 'api-key';
    private const API_SECRET = 'api-secret';

    private MockObject $httpClient;
    private MockObject $clock;
    private Client $client;

    public function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->clock = $this->createMock(Clock::class);
        $this->client = new Client(self::API_KEY, self::API_SECRET, $this->httpClient, $this->clock);
    }

    public function tearDown(): void
    {
        unset(
            $this->httpClient,
            $this->clock,
            $this->client,
        );
    }

    /**
     * @dataProvider requestDataProvider
     * @param mixed[] $responseData
     */
    public function testRequest(bool $isSuccess, array $responseData): void
    {
        $timestamp = '2022-11-11 12:37:45';
        $timezone = 'GMT+8';
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
                'https://api.taobao.com/router/rest',
                [
                    'headers' => [
                        'User-Agent' => 'Kyto Alibaba Client',
                    ],
                    'body' => [
                        'app_key' => self::API_KEY,
                        'timestamp' => $timestamp,
                        'format' => 'json',
                        'v' => '2.0',
                        'hello' => 'world',
                        'test' => 'data',
                        'sign_method' => 'md5',
                        'sign' => 'E6B0CBA032759D6C2A4BC0136252672F',
                    ]
                ]
            )
        ->willReturn($response);

        if (!$isSuccess) {
            $this->expectException(AlibabaApiException::class);
        }

        $actual = $this->client->request(['hello' => 'world', 'test' => 'data']);
        self::assertSame($responseData, $actual);
    }

    /**
     * @return mixed[]
     */
    public function requestDataProvider(): array
    {
        return [
            'success' => [true, ['successful' => 'response']],
            'error' => [false, ['error_response' => [
                'code' => '1',
                'msg' => 'Error happened',
                'sub_code' => 'api.error',
                'sub_msg' => 'Not working'
            ]]],
        ];
    }
}
