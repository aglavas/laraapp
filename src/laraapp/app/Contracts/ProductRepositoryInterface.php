<?php

namespace App\Contracts;

use App\Entities\Company;
use App\Entities\Product;
use App\Filters\ProductFilter;

interface ProductRepositoryInterface
{
    /**
     * Load product relations on product instance
     *
     * @param Product $product
     * @param array $relations
     * @return mixed
     */
    public function loadProductRelations(Product $product, array $relations);

    /**
     * List products by company
     *
     * @param Company $company
     * @return mixed
     */
    public function listProductsByCompany(Company $company);

    /**
     * Create new product by company id
     *
     * @param Company $company
     * @param array $params
     * @return mixed
     */
    public function createProductByCompanyId(Company $company, array $params);

    /**
     * Update product
     *
     * @param Product $product
     * @param array $params
     * @return mixed
     */
    public function updateProduct(Product $product, array $params);

    /**
     * Delete product
     *
     * @param Product $product
     * @return mixed
     */
    public function destroyProduct(Product $product);

    /**
     * Search product using filter
     *
     * @param Product $product
     * @param ProductFilter $filter
     * @return mixed
     */
    public function searchProduct(Product $product, ProductFilter $filter);
}
