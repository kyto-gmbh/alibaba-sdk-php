<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Facade;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FacadeTest extends TestCase
{
    private const API_KEY = 'api-key';
    private const API_SECRET = 'api-secret';

    private MockObject $client;
    private Facade $facade;

    public function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->facade = new Facade(self::API_KEY, $this->client);
    }

    public function tearDown(): void
    {
        unset(
            $this->client,
            $this->facade,
        );
    }

    public function testCreate(): void
    {
        $actual = Facade::create(self::API_KEY, self::API_SECRET);
        self::assertInstanceOf(Facade::class, $actual);
    }

    public function testGetAuthorizationUrl(): void
    {
        $callbackURL = 'https://example.com/callback';
        $actual = $this->facade->getAuthorizationUrl($callbackURL);
        $expected = sprintf(
            'https://oauth.alibaba.com/authorize?response_type=code&client_id=%s&redirect_uri=%s'
                . '&State=1212&view=web&sp=ICBU',
            urlencode(self::API_KEY),
            urlencode($callbackURL),
        );

        self::assertSame($expected, $actual);
    }
}
