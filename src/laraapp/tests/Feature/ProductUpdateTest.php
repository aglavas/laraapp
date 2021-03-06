<?php

namespace Tests\Feature;

use App\Entities\Attribute;
use App\Entities\Product;
use App\Entities\User;
use App\Entities\Company;
use Tests\TestCase;

class ProductUpdateTest extends TestCase
{
    /**
     * @var Product
     */
    private $product;

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

        $this->product = factory(Product::class)->create();
        $this->color = factory(Attribute::class)->create(['name' => 'Color']);
        $this->size = factory(Attribute::class)->create(['name' => 'Size']);

        $this->product->attributes()->sync([
            $this->color->id => [
                'value' => 'black'
            ],
            $this->size->id => [
                'value' => '42'
            ],
        ]);

        $this->product = $this->product->load('attributes');

        $this->signIsUsingPassport();
    }

    /**
     * A product updated successfully.
     *
     * @return void
     */
    public function testProductUpdatedSuccessfully()
    {
        dump('test_product_updated_successfully');

        $this->disableAuthorization();

        $response = $this->updateProduct();

        $response->assertStatus(200);

        $response->assertJsonFragment($this->validFields());
    }

    /**
     * A product updated successfully.
     *
     * @return void
     */
    public function testProductPartiallyUpdatedSuccessfully()
    {
        dump('test_product_partially_updated_successfully');

        $this->disableAuthorization();

        $response = $this->updateProduct(['price']);

        $response->assertStatus(200);

        $successResponse = $this->validFields(['price']);

        $response->assertJsonFragment($successResponse);

        $response = $this->updateProduct(['name', 'qty']);

        $response->assertStatus(200);

        $successResponse = $this->validFields(['name', 'qty']);

        $response->assertJsonFragment($successResponse);

        $response = $this->updateProduct(['attributes']);

        $response->assertStatus(200);

        $successResponse = $this->validFields(['attributes']);

        $response->assertJsonFragment($successResponse);
    }


    /**
     * A product update name validation error.
     *
     * @return void
     */
    public function testProductUpdateNameValidationError()
    {
        dump('test_product_update_name_validation_error');

        $this->disableAuthorization();

        $response = $this->updateProduct(['name' => null], true);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'name' => ['The name must be a string.']
        ]);
    }

    /**
     * A product update price validation error.
     *
     * @return void
     */
    public function testProductUpdatePriceValidationError()
    {
        dump('test_product_update_price_validation_error');

        $this->disableAuthorization();

        $response = $this->updateProduct(['price' => null], true);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'price' => ['The price must be a number.']
        ]);
    }

    /**
     * A product update qty validation error.
     *
     * @return void
     */
    public function testProductUpdateQtyValidationError()
    {
        dump('test_product_update_qty_validation_error');

        $this->disableAuthorization();

        $response = $this->updateProduct(['qty' => null], true);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'qty' => ['The qty must be an integer.']
        ]);
    }


    /**
     * A product update attributes validation error.
     *
     * @return void
     */
    public function testProductUpdateAttributesValidationError()
    {
        dump('test_product_update_attributes_validation_error');

        $this->disableAuthorization();

        $response = $this->updateProduct(['attributes' => null], true);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'attributes' => ['The attributes must be an array.']
        ]);

        $response = $this->updateProduct(['attributes' => [0 => []]], true);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'attributes.0' => ['The attributes.0 field is required.'],
            'attributes.0.id' => ['The attributes.0.id field is required.'],
            'attributes.0.value' => ['The attributes.0.value field is required.'],
        ]);

        $response = $this->updateProduct(['attributes' =>
            [
                0 => [
                        'id' => 999999,
                        'value' => 'test',
                    ]
            ]
        ], true);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'attributes.0.id' => ['The selected attributes.0.id is invalid.'],
        ]);
    }

    /**
     * Test product update authorization cases - successful
     */
    public function testProductUpdateAuthorizationSuccessfully()
    {
        dump('test_product_update_authorization_successfully_user_company_owner');

        $user = $this->product->company->user;

        $this->signIsUsingPassportAsUser($user);

        $response = $this->updateProduct();

        $response->assertStatus(200);

        $response->assertJsonFragment($this->validFields());

        dump('test_product_update_authorization_successfully_user_company_admin');

        $user = factory(User::class)->create();

        $user->userCompanyRole()->create([
            'company_id' => $this->product->company->id,
            'role_id' => $this->admin->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->updateProduct();

        $response->assertStatus(200);

        $response->assertJsonFragment($this->validFields());

        dump('test_product_update_authorization_successfully_company_update_permission');

        $user = factory(User::class)->create();

        $user->userCompanyPermission()->create([
            'company_id' => $this->product->company->id,
            'permission_id' => $this->updatePermission->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->updateProduct();

        $response->assertStatus(200);

        $response->assertJsonFragment($this->validFields());
    }

    /**
     * Test product update authorization cases - unsuccessful
     */
    public function testProductUpdateAuthorizationUnsuccessfully()
    {
        dump('test_product_update_authorization_unsuccessfully_wrong_owner');

        $company = factory(Company::class)->create();

        $user = $company->user;

        $this->signIsUsingPassportAsUser($user);

        $response = $this->updateProduct();

        $response->assertStatus(403);

        dump('test_product_update_authorization_unsuccessfully_guest_role');

        $user = factory(User::class)->create();

        $user->userCompanyRole()->create([
            'company_id' => $this->product->company->id,
            'role_id' => $this->guest->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->updateProduct();

        $response->assertStatus(403);

        dump('test_product_update_authorization_unsuccessfully_view_permission');

        $user = factory(User::class)->create();

        $user->userCompanyPermission()->create([
            'company_id' => $this->product->company->id,
            'permission_id' => $this->viewPermission->id,
        ]);

        $this->signIsUsingPassportAsUser($user);

        $response = $this->updateProduct();

        $response->assertStatus(403);
    }

    /**
     * Send request to store product
     *
     * @param array $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function updateProduct($attributes = [], $custom = false)
    {
        if (!$custom) {
            $response = $this->patch("api/company/{$this->product->company_id}/product/{$this->product->id}", $this->validFields($attributes));
        } else {
            $response = $this->patch("api/company/{$this->product->company_id}/product/{$this->product->id}", $attributes);
        }

        return $response;
    }

    /**
     * Valid payload and modifications
     *
     * @param array $only
     * @return array
     */
    private function validFields($only = [])
    {
        $validFields = [
            'name' => 'Shoes',
            'price' => 15,
            'qty' => 5,
            'company' => $this->product->company->name,
            'attributes' => [
                [
                    'id' => $this->color->id,
                    'value' => 'yellow'
                ],
                [
                    'id' => $this->size->id,
                    'value' => '39'
                ]
            ]
        ];

        $newArray = [];

        if (!count($only)) {
            $newArray = $validFields;
        } else {
            foreach ($only as $field) {
                $newArray[$field] = $validFields[$field];
            }
        }

        return $newArray;
    }
}
