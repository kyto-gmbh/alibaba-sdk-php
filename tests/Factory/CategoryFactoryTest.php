<?php

declare(strict_types=1);

namespace Kyto\Alibaba\Tests\Factory;

use Kyto\Alibaba\Enum\InputType;
use Kyto\Alibaba\Enum\ShowType;
use Kyto\Alibaba\Enum\ValueType;
use Kyto\Alibaba\Factory\CategoryFactory;
use Kyto\Alibaba\Model\Category;
use Kyto\Alibaba\Model\CategoryAttribute;
use Kyto\Alibaba\Model\CategoryAttributeValue;
use Kyto\Alibaba\Model\CategoryLevelAttribute;
use Kyto\Alibaba\Model\CategoryLevelAttributeValue;
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
    public function createCategoryDataProvider(): array
    {
        $cases = [];

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

        $cases['full-result'] = [$data, $model];

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

        $cases['no-parents-and-children'] = [$data, $model];

        return $cases;
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
    public function createAttributeDataProvider(): array
    {
        $cases = [];

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
            'attribute_values' => [
                'attribute_value' => [
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
                ]
            ],
        ];

        $model = new CategoryAttribute();
        $model->id = '1';
        $model->name = 'Example';
        $model->isRequired = true;
        $model->inputType = InputType::SINGLE_SELECT;
        $model->showType = ShowType::LIST_BOX;
        $model->valueType = ValueType::STRING;
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

        $cases['list_box'] = [$data, $model];

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
        $model->inputType = InputType::INPUT;
        $model->showType = ShowType::INPUT;
        $model->valueType = ValueType::NUMBER;
        $model->isSku = false;
        $model->hasCustomizeImage = false;
        $model->hasCustomizeValue = false;
        $model->isCarModel = false;
        $model->units = ['mm', 'cm'];
        $model->values = [];

        $cases['input'] = [$data, $model];

        return $cases;
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
    public function createAttributeValueDataProvider(): array
    {
        $cases = [];

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

        $cases['with-children'] = [$data, $model];

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

        $cases['no-children'] = [$data, $model];

        return $cases;
    }

    /**
     * @dataProvider createLevelAttributeDataProvider
     * @param mixed[] $data
     */
    public function testCreateLevelAttribute(array $data, CategoryLevelAttribute $expected): void
    {
        $actual = $this->categoryFactory->createLevelAttribute($data);
        self::assertEquals($expected, $actual);
    }

    public function createLevelAttributeDataProvider(): \Generator
    {
        $data = [
            'property_id' => '123',
            'property_en_name' => 'someName',
            'values' => '{}'
        ];

        $expected = new CategoryLevelAttribute();
        $expected->id = '123';
        $expected->name = 'someName';
        $expected->values = [];

        yield ['no values' => $data, $expected];

        $data = [
            'property_id' => '123',
            'property_en_name' => 'someName',
            'values' => '[{"id":"1","name":"valueNoLeaf"},{"id":2,"name":"valueIsLeaf","leaf":true}]'
        ];

        $levelValueNoLeaf = new CategoryLevelAttributeValue();
        $levelValueNoLeaf->id = '1';
        $levelValueNoLeaf->name = 'valueNoLeaf';
        $levelValueNoLeaf->isLeaf = false;

        $levelValueIsLeaf = new CategoryLevelAttributeValue();
        $levelValueIsLeaf->id = '2';
        $levelValueIsLeaf->name = 'valueIsLeaf';
        $levelValueIsLeaf->isLeaf = true;

        $expected = new CategoryLevelAttribute();
        $expected->id = '123';
        $expected->name = 'someName';
        $expected->values = [$levelValueNoLeaf, $levelValueIsLeaf];

        yield ['with values' => $data, $expected];
    }

    /**
     * @dataProvider createLevelAttributeValueDataProvider
     * @param mixed[] $data
     */
    public function testCreateLevelAttributeValue(array $data, CategoryLevelAttributeValue $expected): void
    {
        $actual = $this->categoryFactory->createLevelAttributeValue($data);
        self::assertEquals($expected, $actual);
    }

    public function createLevelAttributeValueDataProvider(): \Generator
    {
        $data = [
            "id" => "1",
            "name" => "valueNoLeaf"
        ];

        $expected = new CategoryLevelAttributeValue();
        $expected->name = 'valueNoLeaf';
        $expected->id = '1';
        $expected->isLeaf = false;

        yield ['no leaf' => $data, $expected];

        $data = [
            "id" => "1",
            "name" => "valueIsLeaf",
            "leaf" => true
        ];

        $expected = new CategoryLevelAttributeValue();
        $expected->name = 'valueIsLeaf';
        $expected->id = '1';
        $expected->isLeaf = true;

        yield ['is leaf' => $data, $expected];
    }
}
