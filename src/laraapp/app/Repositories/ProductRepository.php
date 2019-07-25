<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\Entities\Company;
use App\Entities\Product;
use App\Filters\ProductFilter;
use App\Services\Formatter;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var Formatter
     */
    private $formatter;

    /**
     * ProductRepository constructor.
     * @param Formatter $formatter
     */
    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Load product relations on product instance
     *
     * @param Product $product
     * @param array $relations
     * @return Product|mixed
     */
    public function loadProductRelations(Product $product, array $relations)
    {
        return $product->load($relations);
    }

    /**
     * Return product list by company
     *
     * @param Company $company
     * @return Company|mixed
     */
    public function listProductsByCompany(Company $company)
    {
        return $company->load('products');
    }

    /**
     * Create product by company id
     *
     * @param Company $company
     * @param array $params
     * @return Product|mixed
     */
    public function createProductByCompanyId(Company $company, array $params)
    {
        /** @var Product $product */
        $product = $company->products()->create($params);

        $this->syncAttributes($product, $params);

        return $this->loadProductRelations($product, ['attributes']);
    }

    /**
     * Update product and attributes
     *
     * @param Product $product
     * @param array $params
     * @return Product|mixed
     * @throws \Exception
     */
    public function updateProduct(Product $product, array $params)
    {
        $result = $product->update($params);

        if (!$result) {
            throw new \Exception();
        }

        $this->syncAttributes($product, $params);

        return $this->loadProductRelations($product, ['attributes']);
    }

    /**
     * Deletes product
     *
     * @param Product $product
     * @return bool|mixed
     * @throws \Exception
     */
    public function destroyProduct(Product $product)
    {
        $result = $product->delete();

        if (!$result) {
            throw new \Exception();
        }

        return true;
    }

    /**
     * Search product by filter
     *
     * @param Product $product
     * @param ProductFilter $filter
     * @return mixed
     */
    public function searchProduct(Product $product, ProductFilter $filter)
    {
        return $product->filter($filter)->get();
    }

    /**
     * Sync attributes
     *
     * @param Product $product
     * @param array $params
     * @return bool
     */
    private function syncAttributes(Product $product, array $params)
    {
        if (isset($params['attributes'])) {
            $attributesArray = $this->formatter->formatAttributes($params['attributes']);
        } else {
            $attributesArray = [];
        }

        $product->attributes()->sync($attributesArray);

        return true;
    }
}
