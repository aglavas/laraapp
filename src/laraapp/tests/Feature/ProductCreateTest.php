<?php

namespace Tests\Feature;

use App\Entities\Company;
use Tests\TestCase;
use App\Entities\Attribute;
use App\Entities\User;

class ProductCreateTest extends TestCase
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
     * Set up
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();

        $this->color = factory(Attribute::class)->create(['name' => 'Color']);
        $this->size = factory(Attribute::class)->create(['name' => 'Size']);

        $this->signIsUsingPassport();
    }

    /**
     * A product stored successfully.
     *
     * @return void
     */
    public function testProductStoredSuccessfully()
    {
        dump('test_product_stored_successfully');

        $this->disableAuthorization();

        $response = $this->storeProduct();

        $response->assertStatus(201);

        $response->assertJsonFragment($this->validFields());
    }


    /**
     * A product store name validation error.
     *
     * @return void
     */
    public function testProductStoreNameValidationError()
    {
        dump('test_product_store_name_validation_error');

        $this->disableAuthorization();

        $response = $this->storeProduct(['name' => null]);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'name' => ['The name field is required.']
        ]);
    }

    /**
     * A product store price validation error.
     *
     * @return void
     */
    public function testProductStorePriceValidationError()
    {
        dump('test_product_store_price_validation_error');

        $this->disableAuthorization();

        $response = $this->storeProduct(['price' => null]);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'price' => ['The price field is required.']
        ]);

        $response = $this->storeProduct(['price' => 'test']);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'price' => ['The price must be a number.']
        ]);
    }

    /**
     * A product store qty validation error.
     *
     * @return void
     */
    public function testProductStoreQtyValidationError()
    {
        dump('test_product_store_qty_validation_error');

        $this->disableAuthorization();

        $response = $this->storeProduct(['qty' => null]);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'qty' => ['The qty field is required.']
        ]);

        $response = $this->storeProduct(['qty' => '12.5']);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'qty' => ['The qty must be an integer.']
        ]);
    }

    /**
     * A product store attributes validation error.
     *
     * @return void
     */
    public function testProductStoreAttributesValidationError()
    {
        dump('test_product_store_attributes_validation_error');

        $this->disableAuthorization();

        $response = $this->storeProduct(['attributes' => null]);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'attributes' => ['The attributes field is required.']
        ]);

        $response = $this->storeProduct(['attributes' => [0 => []]]);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'attributes.0' => ['The attributes.0 field is required.'],
            'attributes.0.id' => ['The attributes.0.id field is required.'],
            'attributes.0.value' => ['The attributes.0.value field is required.'],
        ]);

        $response = $this->storeProduct(['attributes' =>
            [
                0 => [
                        'id' => 999999,
                        'value' => 'test',
                    ]
            ]
        ]);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'attributes.0.id' => ['The selected attributes.0.id is invalid.'],
        ]);
    }

    /**
     * Test product store authorization cases - successful
     */
    public function testProductStoreAuthorizationSuccessfully()
    {
        dump('test_product_store_authorization_successfully_user_company_owner');

        $user = $this->company->user;

        $this->signIsUsingPassportAsUser($user);

        $response = $this->storeProduct();

        $response->assertStatus(201);

        $response->assertJsonFragment($this->validFields());

        dump('test_product_store_authorization_successfully_user_company_admin');

        $user = factory(User::class)->create();

        $user->userCompanyRole()->create([
            'company_id' => $this->company->id,
            'role_id' => $this->admin->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->storeProduct();

        $response->assertStatus(201);

        $response->assertJsonFragment($this->validFields());

        dump('test_product_store_authorization_successfully_company_update_permission');

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
     * Test product store authorization cases - unsuccessful
     */
    public function testProductStoreAuthorizationUnsuccessfully()
    {
        dump('test_product_store_authorization_unsuccessfully_wrong_owner');

        $company = factory(Company::class)->create();

        $user = $company->user;

        $this->signIsUsingPassportAsUser($user);

        $response = $this->storeProduct();

        $response->assertStatus(403);

        dump('test_product_store_authorization_unsuccessfully_guest_role');

        $user = factory(User::class)->create();

        $user->userCompanyRole()->create([
            'company_id' => $this->company->id,
            'role_id' => $this->guest->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->storeProduct();

        $response->assertStatus(403);

        dump('test_product_store_authorization_unsuccessfully_view_permission');

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
