<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Endpoint;

use Kyto\Alibaba\Client;
use Kyto\Alibaba\Endpoint\CategoryEndpoint;
use Kyto\Alibaba\Factory\CategoryFactory;
use Kyto\Alibaba\Model\Category;
use Kyto\Alibaba\Model\CategoryAttribute;
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
        $id = '1';
        $data = ['response' => 'data'];

        $this->client
            ->expects(self::once())
            ->method('request')
            ->with([
                'method' => 'alibaba.icbu.category.get.new',
                'cat_id' => $id,
            ])
            ->willReturn($data);

        $category = new Category();

        $this->categoryFactory
            ->expects(self::once())
            ->method('createCategory')
            ->with($data)
            ->willReturn($category);

        $actual = $this->categoryEndpoint->get($id);
        self::assertSame($category, $actual);
    }

    public function testGetAttributes(): void
    {
        $id = '1';
        $attributes = [
            ['Attribute 1'],
            ['Attribute 2'],
        ];
        $data = ['alibaba_icbu_category_attribute_get_response' => ['attributes' => ['attribute' => $attributes]]];

        $this->client
            ->expects(self::once())
            ->method('request')
            ->with([
                'method' => 'alibaba.icbu.category.attribute.get',
                'cat_id' => $id,
            ])
            ->willReturn($data);

        $result = [
            new CategoryAttribute(),
            new CategoryAttribute(),
        ];

        $this->categoryFactory
            ->expects(self::exactly(2))
            ->method('createAttribute')
            ->withConsecutive(...array_map(static fn($item) => [$item], $attributes))
            ->willReturnOnConsecutiveCalls(...$result);

        $actual = $this->categoryEndpoint->getAttributes($id);
        self::assertSame($result, $actual);
    }
}
