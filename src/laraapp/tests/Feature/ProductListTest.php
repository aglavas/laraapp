<?php

namespace Tests\Feature;

use App\Entities\Company;
use App\Entities\Product;
use App\Http\Resources\CompanyListProducts\CompanyListProducts;
use App\Http\Resources\CompanyListProducts\ProductListResource;
use App\Http\Resources\Product\ProductResource;
use Tests\TestCase;

class ProductListTest extends TestCase
{
    /**
     * @var Company
     */
    private $company;

    /**
     * @var Product
     */
    private $products;

    /**
     * Set up
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();

        $this->products = factory(Product::class, 10)->create([
            'company_id' => $this->company->id
        ]);

        $this->signIsUsingPassport();
    }

    /**
     * A product list successfully.
     *
     * @return void
     */
    public function test_product_list_successfully()
    {
        dump('test_product_list_successfully');

        $response = $this->listProducts();

        $response->assertStatus(200);

        $resourceProducts = CompanyListProducts::make($this->company)->response()->getData(true);

        $response->assertStatus(200);
        $response->assertJsonFragment($resourceProducts['data']);
        $response->assertJsonCount(10, 'data.products');
    }

    /**
     * Send request to show product
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function listProducts()
    {
        $response = $this->get("api/company/{$this->company->id}/products");

        return $response;
    }
}
