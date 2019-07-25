<?php

namespace Tests\Feature;

use App\Entities\Company;
use App\Entities\Product;
use App\Entities\User;
use App\Http\Resources\CompanyListProducts\CompanyListProducts;
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
    public function testProductListSuccessfully()
    {
        dump('test_product_list_successfully');

        $this->disableAuthorization();

        $response = $this->listProducts();

        $response->assertStatus(200);

        $resourceProducts = CompanyListProducts::make($this->company)->response()->getData(true);

        $response->assertStatus(200);
        $response->assertJsonFragment($resourceProducts['data']);
        $response->assertJsonCount(10, 'data.products');
    }

    /**
     * Test product list authorization cases - successful
     */
    public function testProductListAuthorizationSuccessfully()
    {
        dump('test_product_list_authorization_successfully_company_update_permission');

        $user = factory(User::class)->create();

        $user->userCompanyPermission()->create([
            'company_id' => $this->company->id,
            'permission_id' => $this->updatePermission->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->listProducts();

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data.products');

        dump('test_product_list_authorization_successfully_company_view_permission');

        $user = factory(User::class)->create();

        $user->userCompanyPermission()->create([
            'company_id' => $this->company->id,
            'permission_id' => $this->viewPermission->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->listProducts();

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data.products');
    }

    /**
     * Test product list authorization cases - unsuccessful
     */
    public function testProductListAuthorizationUnsuccessfully()
    {
        dump('test_product_retrieve_authorization_unsuccessfully_no_permissions');

        $user = factory(User::class)->create();

        $this->signIsUsingPassportAsUser($user);

        $response = $this->listProducts();

        $response->assertStatus(403);
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
