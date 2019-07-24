<?php

namespace Tests\Feature;

use App\Entities\User;
use Tests\TestCase;
use App\Entities\Company;
use App\Entities\Attribute;
use App\Entities\Product;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthorizationTest extends TestCase
{
    /**
     * @var Company
     */
    private $company;

    /**
     * @var Attribute
     */
    private $color;

    /**
     * @var Attribute
     */
    private $size;

    /**
     * @var Role
     */
    private $admin;

    /**
     * @var Role
     */
    private $guest;

    /**
     * @var Permission
     */
    private $updatePermission;

    /**
     * @var Permission
     */
    private $viewPermission;

    /**
     * Set up
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();

        $this->color = factory(Attribute::class)->create(['name' => 'Color']);
        $this->size = factory(Attribute::class)->create(['name' => 'Size']);
        $this->admin = Role::where('name', 'admin')->first();
        $this->guest = Role::where('name', 'guest')->first();
        $this->updatePermission = Permission::where('name', 'update')->first();
        $this->viewPermission = Permission::where('name', 'view')->first();

        factory(Product::class, 10)->create([
            'company_id' => $this->company->id
        ]);
    }

    /**
     * Test product manage authorization cases - successful
     */
    public function test_product_manage_authorization_successfully()
    {
        dump('test_product_manage_authorization_successfully_user_company_owner');

        $user = $this->company->user;

        $this->signIsUsingPassportAsUser($user);

        $response = $this->storeProduct();

        $response->assertStatus(201);

        $response->assertJsonFragment($this->validFields());

        dump('test_product_manage_authorization_successfully_user_company_admin');

        $user = factory(User::class)->create();

        $user->userCompanyRole()->create([
            'company_id' => $this->company->id,
            'role_id' => $this->admin->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->storeProduct();

        $response->assertStatus(201);

        $response->assertJsonFragment($this->validFields());

        dump('test_product_manage_authorization_successfully_company_update_permission');

        $user = factory(User::class)->create();

        $user->userCompanyPermission()->create([
            'company_id' => $this->company->id,
            'permission_id' => $this->updatePermission->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->storeProduct();

        $response->assertStatus(201);

        $response->assertJsonFragment($this->validFields());
    }

    /**
     * Test product manage authorization cases - unsuccessful
     */
    public function test_product_manage_authorization_unsuccessfully()
    {
        dump('test_product_manage_authorization_unsuccessfully_wrong_owner');

        $company = factory(Company::class)->create();

        $user = $company->user;

        $this->signIsUsingPassportAsUser($user);

        $response = $this->storeProduct();

        $response->assertStatus(403);

        dump('test_product_manage_authorization_unsuccessfully_guest_role');

        $user = factory(User::class)->create();

        $user->userCompanyRole()->create([
            'company_id' => $this->company->id,
            'role_id' => $this->guest->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->storeProduct();

        $response->assertStatus(403);

        dump('test_product_manage_authorization_unsuccessfully_view_permission');

        $user = factory(User::class)->create();

        $user->userCompanyPermission()->create([
            'company_id' => $this->company->id,
            'permission_id' => $this->viewPermission->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->storeProduct();

        $response->assertStatus(403);
    }

    /**
     * Test product retrieve authorization cases - successful
     */
    public function test_product_retrieve_authorization_successfully()
    {
        dump('test_product_retrieve_authorization_successfully_company_update_permission');

        $user = factory(User::class)->create();

        $user->userCompanyPermission()->create([
            'company_id' => $this->company->id,
            'permission_id' => $this->updatePermission->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->listProducts();

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data.products');

        dump('test_product_retrieve_authorization_successfully_company_view_permission');

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
     * Test product retrieve authorization cases - unsuccessful
     */
    public function test_product_retrieve_authorization_unsuccessfully()
    {
        dump('test_product_retrieve_authorization_unsuccessfully_no_permissions');

        $user = factory(User::class)->create();

        $this->signIsUsingPassportAsUser($user);

        $response = $this->listProducts();

        $response->assertStatus(403);
    }

    /**
     * Send request to store product
     *
     * @param array $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function storeProduct($attributes = [])
    {
        $response = $this->post("api/company/{$this->company->id}/product", $this->validFields($attributes));

        return $response;
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

    /**
     * Valid payload and modifications
     *
     * @param array $overrides
     * @return array
     */
    private function validFields($overrides = [])
    {
        return array_merge([
            'name' => 'Shoes',
            'price' => 15,
            'qty' => 5,
            'attributes' => [
                [
                    'id' => $this->color->id,
                    'value' => 'black'
                ],
                [
                    'id' => $this->size->id,
                    'value' => '42'
                ]
            ]
        ], $overrides);
    }
}
