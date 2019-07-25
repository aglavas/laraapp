<?php

namespace App\Http\Controllers;

use App\Contracts\ProductRepositoryInterface;
use App\Entities\Company;
use App\Entities\Product;
use App\Exceptions\AttributeFormatException;
use App\Filters\ProductFilter;
use App\Http\Requests\Product\ProductDestroyRequest;
use App\Http\Requests\Product\ProductListRequest;
use App\Http\Requests\Product\ProductSearchRequest;
use App\Http\Requests\Product\ProductShowRequest;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Resources\CompanyListProducts\CompanyListProducts;
use App\Http\Resources\Product\ProductResource;

class ProductController extends Controller
{
    /**
     * Returns product single instance
     *
     * @param ProductShowRequest $request
     * @param Company $company
     * @param Product $product
     * @param ProductRepositoryInterface $productRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(
        ProductShowRequest $request,
        Company $company,
        Product $product,
        ProductRepositoryInterface $productRepository
    ) {
        $product = $productRepository->loadProductRelations($product, ['attributes']);

        $productFormatted = ProductResource::make($product)->toArray($request);

        return $this->successDataResponse($productFormatted, 200);
    }

    /**
     * Lists products by company
     *
     * @param ProductListRequest $request
     * @param Company $company
     * @param ProductRepositoryInterface $productRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(
        ProductListRequest $request,
        Company $company,
        ProductRepositoryInterface $productRepository
    ) {
        $products = $productRepository->listProductsByCompany($company);

        $productsFormatted = CompanyListProducts::make($products)->toArray($request);

        return $this->successDataResponse($productsFormatted, 200);
    }

    /**
     * Create new product entity
     *
     * @param ProductStoreRequest $request
     * @param Company $company
     * @param ProductRepositoryInterface $productRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(
        ProductStoreRequest $request,
        Company $company,
        ProductRepositoryInterface $productRepository
    ) {
        try {
            $product = $productRepository->createProductByCompanyId($company, $request->input());

            $productFormatted = ProductResource::make($product)->toArray($request);
        } catch (\Exception $exception) {
            return $this->errorMessageResponse('Error while saving product.', 500);
        }

        return $this->successDataResponse($productFormatted, 201);
    }

    /**
     * Update product entity
     *
     * @param ProductUpdateRequest $request
     * @param Company $company
     * @param Product $product
     * @param ProductRepositoryInterface $productRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(
        ProductUpdateRequest $request,
        Company $company,
        Product $product,
        ProductRepositoryInterface $productRepository
    ) {
        try {
            $product = $productRepository->updateProduct($product, $request->input());

            $productFormatted = ProductResource::make($product)->toArray($request);
        } catch (\Exception $exception) {
            return $this->errorMessageResponse('Error while updating product.', 500);
        }

        return $this->successDataResponse($productFormatted, 200);
    }

    /**
     * Deletes product entity
     *
     * @param ProductDestroyRequest $request
     * @param Company $company
     * @param Product $product
     * @param ProductRepositoryInterface $productRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(
        ProductDestroyRequest $request,
        Company $company,
        Product $product,
        ProductRepositoryInterface $productRepository
    ) {
        try {
            $productRepository->destroyProduct($product);
        } catch (\Exception $exception) {
            return $this->errorMessageResponse('Error while deleting product.', 500);
        }

        return $this->successMessageResponse('', 204);
    }

    /**
     * Searches product entity
     *
     * @param ProductSearchRequest $request
     * @param Product $product
     * @param ProductFilter $filter
     * @param ProductRepositoryInterface $productRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts(
        ProductSearchRequest $request,
        Product $product,
        ProductFilter $filter,
        ProductRepositoryInterface $productRepository
    ) {
        try {
            $products = $productRepository->searchProduct($product, $filter);

            $productFormatted = ProductResource::collection($products)->toArray($request);
        } catch (AttributeFormatException $exception) {
            $productFormatted = [];
        }

        return $this->successDataResponse($productFormatted, 200);
    }
}
