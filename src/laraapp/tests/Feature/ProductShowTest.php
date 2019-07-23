<?php

namespace Tests\Feature;

use App\Entities\Attribute;
use App\Entities\Product;
use App\Http\Resources\Product\ProductResource;
use Tests\TestCase;

class ProductShowTest extends TestCase
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
        $color = factory(Attribute::class)->create(['name' => 'Color']);
        $size = factory(Attribute::class)->create(['name' => 'Size']);

        $this->product->attributes()->sync([
            $color->id => [
                'value' => 'black'
            ],
            $size->id => [
                'value' => '42'
            ],
        ]);

        $this->product = $this->product->load('attributes');

        $this->signIsUsingPassport();
    }

    /**
     * A product show successfully.
     *
     * @return void
     */
    public function test_product_show_successfully()
    {
        dump('test_product_show_successfully');

        $response = $this->showProduct();

        $response->assertStatus(200);

        $resourceProduct = ProductResource::make($this->product)->response()->getData(true);

        $response->assertJsonFragment($resourceProduct['data']);
    }

    /**
     * Send request to show product
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function showProduct()
    {
        $response = $this->get("api/company/{$this->product->company_id}/product/{$this->product->id}");

        return $response;
    }
}
