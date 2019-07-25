<?php

namespace Tests\Feature;

use App\Entities\Attribute;
use App\Entities\Product;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    /**
     * Set up
     */
    public function setUp(): void
    {
        parent::setUp();

        /** @var Collection $products */
        $products = factory(Product::class, 10)->create(['price' => 50, 'qty' => 15]);
        $products2 = factory(Product::class, 10)->create(['price' => 100, 'qty' => 100]);
        $products = $products->merge($products2);
        $products3 = factory(Product::class, 7)->create(['name' => 'Test999', 'price' => 10, 'qty' => 33]);
        $products = $products->merge($products3);

        $color = factory(Attribute::class)->create(['name' => 'Color']);
        $size = factory(Attribute::class)->create(['name' => 'Size']);

        $productsForAttributes = $products->take(10);

        $productsForAttributes->each(function ($product, $key) use ($color, $size) {
            if ($key < 3) {
                $product->attributes()->sync([
                    $color->id => ['value' => 'black'],
                    $size->id => ['value' => '40'],
                ]);
            }

            if (($key > 3) && ($key < 6)) {
                $product->attributes()->sync([
                    $color->id => ['value' => 'yellow'],
                    $size->id => ['value' => '38'],
                ]);
            }

            if ($key > 6) {
                $product->attributes()->sync([
                    $color->id => ['value' => 'green'],
                    $size->id => ['value' => '42'],
                ]);
            }
        });

        $this->assertCount(27, $products);

        $this->signIsUsingPassport();

        $this->disableAuthorization();
    }

    /**
     * Check name query param
     */
    public function testProductSearchName()
    {
        dump('test_product_search_successfully_name');

        $response = $this->searchProduct(['name' => 'Test999']);

        $response->assertJsonCount(7, 'data');

        $response->assertStatus(200);
    }

    /**
     * Check price query param
     */
    public function testProductSearchPrice()
    {
        dump('test_product_search_successfully_price');

        $response = $this->searchProduct(['priceGt' => '20']);

        $response->assertJsonCount(20, 'data');

        $response->assertStatus(200);

        $response = $this->searchProduct(['priceEq' => '50']);

        $response->assertJsonCount(10, 'data');

        $response->assertStatus(200);

        $response = $this->searchProduct(['priceEq' => '10']);

        $response->assertJsonCount(7, 'data');

        $response->assertStatus(200);

        $response = $this->searchProduct(['priceLt' => '120']);

        $response->assertJsonCount(27, 'data');

        $response->assertStatus(200);
    }

    /**
     * Check qty query param
     */
    public function testProductSearchQty()
    {
        dump('test_product_search_successfully_qty');

        $response = $this->searchProduct(['qtyEq' => '15']);

        $response->assertJsonCount(10, 'data');

        $response->assertStatus(200);

        $response = $this->searchProduct(['qtyEq' => '33']);

        $response->assertJsonCount(7, 'data');

        $response->assertStatus(200);

        $response = $this->searchProduct(['qtyGt' => '20']);

        $response->assertJsonCount(17, 'data');

        $response->assertStatus(200);

        $response = $this->searchProduct(['qtyLt' =>'120']);

        $response->assertJsonCount(27, 'data');

        $response->assertStatus(200);
    }

    /**
     * Check attribute query param
     */
    public function testProductSearchAttributes()
    {
        dump('test_product_search_successfully_attributes');

        $response = $this->searchProduct(['attribute' => '1:black']);

        $response->assertJsonCount(3, 'data');

        $response->assertStatus(200);

        $response = $this->searchProduct(['attribute' => '1:black;1:yellow']);

        $response->assertJsonCount(5, 'data');

        $response->assertStatus(200);

        $response = $this->searchProduct(['attribute' => '1:black;1:yellow;1:green']);

        $response->assertJsonCount(8, 'data');

        $response->assertStatus(200);

        $response = $this->searchProduct(['attribute' => '1:black;2:38']);

        $response->assertJsonCount(5, 'data');

        $response->assertStatus(200);

        $response = $this->searchProduct(['attribute' => '1:black;2:38;2:40']);

        $response->assertJsonCount(5, 'data');

        $response->assertStatus(200);

        $response = $this->searchProduct(['attribute' => '1:black;2:38;2:40;1:green']);

        $response->assertJsonCount(8, 'data');

        $response->assertStatus(200);
    }

    /**
     * Test broken search params
     */
    public function testProductSearchBrokenParams()
    {
        dump('test_product_search_broken_params');

        $this->disableAuthorization();

        $response = $this->searchProduct(['name' => '1:black;;']);

        $response->assertJsonCount(0, 'data');

        $response->assertStatus(200);
    }

    /**
     * Send request to store product
     *
     * @param $payload
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function searchProduct($payload)
    {
        $response = $this->json('GET', "/api/products", $payload);

        return $response;
    }
}
