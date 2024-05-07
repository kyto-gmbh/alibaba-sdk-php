<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Factory;

use Kyto\Alibaba\Factory\CategoryFactory;
use Kyto\Alibaba\Model\Category;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CategoryFactoryTest extends TestCase
{
    private CategoryFactory $categoryFactory;

    public function setUp(): void
    {
        $this->categoryFactory = new CategoryFactory();
    }

    public function tearDown(): void
    {
        unset(
            $this->categoryFactory,
        );
    }

    #[DataProvider('createCategoryDataProvider')]
    public function testCreateCategory(array $data, Category $expected): void
    {
        $actual = $this->categoryFactory->createCategory($data);
        self::assertEquals($expected, $actual);
    }

    public static function createCategoryDataProvider(): \Generator
    {
        $data = [
            'result' => [
                'result' => [
                    'category_id' => 1,
                    'name' => 'Example',
                    'cn_name' => '例子',
                    'level' => 2,
                    'leaf_category' => false,
                    'parent_ids' => [2, 3],
                    'child_ids' => [4, 5],
                ],
            ],
        ];

        $model = new Category();
        $model->id = '1';
        $model->name = 'Example';
        $model->nameCN = '例子';
        $model->level = 2;
        $model->isLeaf = false;
        $model->parents = ['2', '3'];
        $model->children = ['4', '5'];

        yield 'full-result' => [$data, $model];

        $data = [
            'result' => [
                'result' => [
                    'category_id' => 1,
                    'name' => 'Example',
                    'cn_name' => '例子',
                    'level' => 2,
                    'leaf_category' => false,
                ],
            ],
        ];

        $model = new Category();
        $model->id = '1';
        $model->name = 'Example';
        $model->nameCN = '例子';
        $model->level = 2;
        $model->isLeaf = false;
        $model->parents = [];
        $model->children = [];

        yield 'no-parents-and-children' => [$data, $model];
    }
}
