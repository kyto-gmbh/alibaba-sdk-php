<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Factory;

use Kyto\Alibaba\Factory\CategoryFactory;
use Kyto\Alibaba\Model\Category;
use Kyto\Alibaba\Model\CategoryAttribute;
use Kyto\Alibaba\Model\CategoryAttributeValue;
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

    /**
     * @dataProvider createCategoryDataProvider
     * @param mixed[] $data
     */
    public function testCreateCategory(array $data, Category $expected): void
    {
        $actual = $this->categoryFactory->createCategory($data);
        self::assertEquals($expected, $actual);
    }

    /**
     * @return mixed[]
     */
    public function createCategoryDataProvider(): iterable
    {
        // Full result
        $data = [
            'alibaba_icbu_category_get_new_response' => [
                'category' => [
                    'category_id' => 1,
                    'name' => 'Example',
                    'cn_name' => '例子',
                    'level' => 2,
                    'leaf_category' => false,
                    'parent_ids' => ['number' => [2, 3]],
                    'child_ids' => ['number' => [4, 5]],
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

        yield [$data, $model];

        // No parents and children
        $data = [
            'alibaba_icbu_category_get_new_response' => [
                'category' => [
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

        yield [$data, $model];
    }

    /**
     * @dataProvider createAttributeDataProvider
     * @param mixed[] $data
     */
    public function testCreateAttribute(array $data, CategoryAttribute $expected): void
    {
        $actual = $this->categoryFactory->createAttribute($data);
        self::assertEquals($expected, $actual);
    }

    /**
     * @return mixed[]
     */
    public function createAttributeDataProvider(): iterable
    {
        // list_box
        $data = [
            'attr_id' => 1,
            'en_name' => 'Example',
            'required' => true,
            'input_type' => 'single_select',
            'show_type' => 'list_box',
            'value_type' => 'string',
            'sku_attribute' => false,
            'customize_image' => false,
            'customize_value' => false,
            'car_model' => false,
            'attribute_values' => ['attribute_value' => [
                [
                    'attr_value_id' => 11,
                    'en_name' => 'Value 1',
                    'sku_value' => false,
                    'child_attrs' => [
                        'number' => [2, 3]
                    ]
                ],
                [
                    'attr_value_id' => 12,
                    'en_name' => 'Value 2',
                    'sku_value' => true,
                ],
            ]],
        ];

        $model = new CategoryAttribute();
        $model->id = '1';
        $model->name = 'Example';
        $model->isRequired = true;
        $model->inputType = 'single_select';
        $model->showType = 'list_box';
        $model->valueType = 'string';
        $model->isSku = false;
        $model->hasCustomizeImage = false;
        $model->hasCustomizeValue = false;
        $model->isCarModel = false;
        $model->units = [];

        $value1 = new CategoryAttributeValue();
        $value1->id = '11';
        $value1->name = 'Value 1';
        $value1->isSku = false;
        $value1->childAttributes = ['2', '3'];

        $value2 = new CategoryAttributeValue();
        $value2->id = '12';
        $value2->name = 'Value 2';
        $value2->isSku = true;
        $value2->childAttributes = [];

        $model->values = [$value1, $value2];

        yield [$data, $model];

        // input
        $data = [
            'attr_id' => 1,
            'en_name' => 'Example',
            'required' => true,
            'input_type' => 'input',
            'show_type' => 'input',
            'value_type' => 'number',
            'sku_attribute' => false,
            'customize_image' => false,
            'customize_value' => false,
            'car_model' => false,
            'units' => ['string' => ['mm', 'cm']],
        ];

        $model = new CategoryAttribute();
        $model->id = '1';
        $model->name = 'Example';
        $model->isRequired = true;
        $model->inputType = 'input';
        $model->showType = 'input';
        $model->valueType = 'number';
        $model->isSku = false;
        $model->hasCustomizeImage = false;
        $model->hasCustomizeValue = false;
        $model->isCarModel = false;
        $model->units = ['mm', 'cm'];
        $model->values = [];

        yield [$data, $model];
    }

    /**
     * @dataProvider createAttributeValueDataProvider
     * @param mixed[] $data
     */
    public function testCreateAttributeValue(array $data, CategoryAttributeValue $expected): void
    {
        $actual = $this->categoryFactory->createAttributeValue($data);
        self::assertEquals($expected, $actual);
    }

    /**
     * @return mixed[]
     */
    public function createAttributeValueDataProvider(): iterable
    {
        // With children
        $data = [
            'attr_value_id' => 11,
            'en_name' => 'Value 1',
            'sku_value' => false,
            'child_attrs' => [
                'number' => [2, 3]
            ]
        ];

        $model = new CategoryAttributeValue();
        $model->id = '11';
        $model->name = 'Value 1';
        $model->isSku = false;
        $model->childAttributes = ['2', '3'];

        yield [$data, $model];

        // No children
        $data = [
            'attr_value_id' => 11,
            'en_name' => 'Value 1',
            'sku_value' => false,
        ];

        $model = new CategoryAttributeValue();
        $model->id = '11';
        $model->name = 'Value 1';
        $model->isSku = false;
        $model->childAttributes = [];

        yield [$data, $model];
    }
}
