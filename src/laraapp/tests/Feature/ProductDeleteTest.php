<?php

namespace Tests\Feature;

use App\Entities\Company;
use App\Entities\Product;
use App\Entities\User;
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
    public function testProductDeletedSuccessfully()
    {
        dump('test_product_deleted_successfully');

        $this->disableAuthorization();

        $response = $this->deleteProduct();

        $response->assertStatus(204);
    }

    /**
     * Test product delete authorization cases - company owner
     */
    public function testDeleteAuthorizationSuccessfullyCompanyOwner()
    {
        dump('test_product_delete_authorization_successfully_user_company_owner');

        $user = $this->product->company->user;

        $this->signIsUsingPassportAsUser($user);

        $response = $this->deleteProduct();

        $response->assertStatus(204);
    }

    /**
     * Test product delete authorization cases - company admin
     */
    public function testDeleteAuthorizationSuccessfullyCompanyAdmin()
    {
        dump('test_product_delete_authorization_successfully_user_company_admin');

        $user = factory(User::class)->create();

        $user->userCompanyRole()->create([
            'company_id' => $this->product->company->id,
            'role_id' => $this->admin->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->deleteProduct();

        $response->assertStatus(204);
    }

    /**
     * Test product delete authorization cases - update permission
     */
    public function testDeleteAuthorizationSuccessfullyCompanyUpdatePermission()
    {
        dump('test_product_delete_authorization_successfully_company_update_permission');

        $user = factory(User::class)->create();

        $user->userCompanyPermission()->create([
            'company_id' => $this->product->company->id,
            'permission_id' => $this->updatePermission->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->deleteProduct();

        $response->assertStatus(204);
    }

    /**
     * Test product store authorization cases - unsuccessful
     */
    public function testProductDeleteAuthorizationUnsuccessfully()
    {
        dump('test_product_delete_authorization_unsuccessfully_wrong_owner');

        $company = factory(Company::class)->create();

        $user = $company->user;

        $this->signIsUsingPassportAsUser($user);

        $response = $this->deleteProduct();

        $response->assertStatus(403);

        dump('test_product_delete_authorization_unsuccessfully_guest_role');

        $user = factory(User::class)->create();

        $user->userCompanyRole()->create([
            'company_id' => $this->product->company->id,
            'role_id' => $this->guest->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->deleteProduct();

        $response->assertStatus(403);

        dump('test_product_delete_authorization_unsuccessfully_view_permission');

        $user = factory(User::class)->create();

        $user->userCompanyPermission()->create([
            'company_id' => $this->product->company->id,
            'permission_id' => $this->viewPermission->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->deleteProduct();

        $response->assertStatus(403);
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
