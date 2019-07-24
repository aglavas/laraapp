<?php

namespace Tests\Feature;

use App\Entities\Company;
use App\Entities\Product;
use Tests\TestCase;

class ProductDeleteTest extends TestCase
{
    /**
     * @var Product
     */
    private $product;

    /**
     * Set up
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->product = factory(Product::class)->create();

        $this->signIsUsingPassport();
    }

    /**
     * A product deleted successfully.
     *
     * @return void
     */
    public function test_product_deleted_successfully()
    {
        dump('test_product_deleted_successfully');

        $this->disableAuthorization();

        $response = $this->deleteProduct();

        $response->assertStatus(204);
    }

    /**
     * Send request to delete product
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function deleteProduct()
    {
        $response = $this->delete("api/company/{$this->product->company_id}/product/{$this->product->id}");

        return $response;
    }
}
