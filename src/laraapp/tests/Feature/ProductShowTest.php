<?php

namespace Tests\Feature;

use App\Entities\Attribute;
use App\Entities\Product;
use App\Entities\User;
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
    public function testProductShowSuccessfully()
    {
        dump('test_product_show_successfully');

        $this->disableAuthorization();

        $response = $this->showProduct();

        $response->assertStatus(200);

        $resourceProduct = ProductResource::make($this->product)->response()->getData(true);

        $response->assertJsonFragment($resourceProduct['data']);
    }

    /**
     * Test product show authorization cases - successful
     */
    public function testProductShowAuthorizationSuccessfully()
    {
        dump('test_product_show_authorization_successfully_company_update_permission');

        $user = factory(User::class)->create();

        $user->userCompanyPermission()->create([
            'company_id' => $this->product->company->id,
            'permission_id' => $this->updatePermission->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->showProduct();

        $response->assertStatus(200);

        $resourceProduct = ProductResource::make($this->product)->response()->getData(true);

        $response->assertJsonFragment($resourceProduct['data']);

        dump('test_product_show_authorization_successfully_company_view_permission');

        $user = factory(User::class)->create();

        $user->userCompanyPermission()->create([
            'company_id' => $this->product->company->id,
            'permission_id' => $this->viewPermission->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->showProduct();

        $response->assertStatus(200);

        $resourceProduct = ProductResource::make($this->product)->response()->getData(true);

        $response->assertJsonFragment($resourceProduct['data']);
    }

    /**
     * Test product show authorization cases - unsuccessful
     */
    public function testProductShowAuthorizationUnsuccessfully()
    {
        dump('test_product_show_authorization_unsuccessfully_no_permissions');

        $user = factory(User::class)->create();

        $this->signIsUsingPassportAsUser($user);

        $response = $this->showProduct();

        $response->assertStatus(403);
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
