<?php

declare(strict_types=1);

namespace Kyto\Alibaba;

use Kyto\Alibaba\Model\Category;
use Kyto\Alibaba\Model\CategoryAttribute;
use Kyto\Alibaba\Model\CategoryAttributeValue;
use Symfony\Component\HttpClient\HttpClient;

class Facade
{
    /**
     * @param string $apiKey Also referenced as "app key" in the Alibaba docs
     * @param string $secret Also referenced as "app security" in the Alibaba docs
     */
    public static function create(string $apiKey, string $secret): self
    {
        return new self(
            $apiKey,
            new Client($apiKey, $secret, HttpClient::create()),
        );
    }

    public function __construct(
        private string $apiKey,
        private Client $client,
    ) {
    }

    /**
     * Making GET request to this URL will ask to login to Alibaba and authorize this API key to have access to the account.
     * In other words client should visit this url and authorize App to access Alibaba account by API.
     *
     * @param string $callbackUrl URL where authorization code returned. Via method GET in "code" parameter.
     */
    public function getAuthorizationUrl(string $callbackUrl): string
    {
        return 'https://oauth.alibaba.com/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $this->apiKey,
            'redirect_uri' => $callbackUrl,
            'State' => '1212',
            'view' => 'web',
            'sp' => 'ICBU',
        ]);
    }

    /**
     * @param string $id Provide '0' to fetch root categories
     */
    public function getCategory(string $id): Category
    {
        $data = $this->client->request([
            'method' => 'alibaba.icbu.category.get.new',
            'cat_id' => $id,
        ]);

        // TODO: validate response

        $category = $data['alibaba_icbu_category_get_new_response']['category'];

        $model = new Category();
        $model->id = (string) $category['category_id'];
        $model->name = (string) $category['name'];
        $model->nameCN = (string) ($category['cn_name'] ?? '');
        $model->level = (int) $category['level'];
        $model->isLeaf = (bool) $category['leaf_category'];
        $model->parentIds = array_map(static fn($item) => (string) $item, $category['parent_ids']['number'] ?? []);
        $model->childIds = array_map(static fn($item) => (string) $item, $category['child_ids']['number'] ?? []);

        return $model;
    }

    public function getCategoryAttributes(string $categotyId): array
    {
        $data = $this->client->request([
            'method' => 'alibaba.icbu.category.attribute.get',
            'cat_id' => $categotyId,
        ]);

        // TODO: validate response

        $result = [];

        $attributes = $data['alibaba_icbu_category_attribute_get_response']['attributes']['attribute'];
        foreach ($attributes as $attribute) {
            $model = new CategoryAttribute();
            $model->id = (string) $attribute['attr_id'];
            $model->name = (string) $attribute['en_name'];
            $model->isRequired = (bool) $attribute['required'];

            // TODO: change to constants / enum

            /**
             * Known values:
             * single_select, multi_select, input
             */
            $model->inputType = (string) $attribute['input_type'];

            /**
             * Known values:
             * list_box (single_select), check_box (multi_select), input (input)
             */
            $model->showType = (string) $attribute['show_type'];

            /**
             * Known values:
             * string, number
             */
            $model->valueType = (string) $attribute['value_type'];

            $model->isSku = (bool) $attribute['sku_attribute'];
            $model->hasCustomizeImage = (bool) $attribute['customize_image'];
            $model->hasCustomizeValue = (bool) $attribute['customize_value'];
            $model->isCarModel = (bool) $attribute['car_model'];

            $model->units = array_map(static fn($item) => (string) $item, $attribute['units']['string'] ?? []);

            $values = $attribute['attribute_values']['attribute_value'] ?? [];
            foreach ($values as $value) {
                $valueModel = new CategoryAttributeValue();
                $valueModel->id = (string) $value['attr_value_id'];
                $valueModel->name = (string) $value['en_name'];
                $valueModel->isSku = (bool) $value['sku_value'];
                $valueModel->childAttributeIds = array_map(static fn($item) => (string) $item, $attribute['child_attrs']['number'] ?? []);
                $model->values[] = $valueModel;
            }

            $result[] = $model;
        }

        return $result;
    }
}
