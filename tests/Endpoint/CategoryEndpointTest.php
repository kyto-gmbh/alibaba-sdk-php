<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Endpoint;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Endpoint\CategoryEndpoint;
use Kyto\Alibaba\Factory\CategoryFactory;
use Kyto\Alibaba\Model\Category;
use Kyto\Alibaba\Model\Token;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryEndpointTest extends TestCase
{
    private MockObject $client;
    private MockObject $categoryFactory;
    private CategoryEndpoint $categoryEndpoint;

    public function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->categoryFactory = $this->createMock(CategoryFactory::class);
        $this->categoryEndpoint = new CategoryEndpoint($this->client, $this->categoryFactory);
    }

    public function tearDown(): void
    {
        unset(
            $this->client,
            $this->categoryFactory,
            $this->categoryEndpoint,
        );
    }

    public function testCreate(): void
    {
        $actual = CategoryEndpoint::create($this->createMock(Client::class));
        self::assertInstanceOf(CategoryEndpoint::class, $actual);
    }

    public function testGet(): void
    {
        $accessToken = 'access-token';
        $id = '1';
        $data = ['response' => 'data'];

        $this->client
            ->expects(self::once())
            ->method('request')
            ->with(
                '/icbu/product/category/get',
                [
                    'access_token' => $accessToken,
                    'cat_id' => $id,
                ]
            )
            ->willReturn($data);

        $category = new Category();

        $this->categoryFactory
            ->expects(self::once())
            ->method('createCategory')
            ->with($data)
            ->willReturn($category);

        $token = new Token();
        $token->token = $accessToken;

        $actual = $this->categoryEndpoint->get($token, $id);
        self::assertSame($category, $actual);
    }
}
