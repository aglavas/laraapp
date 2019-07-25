<?php

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ownerRole = \Spatie\Permission\Models\Role::where('name', 'owner')->first();

        factory(\App\Entities\Company::class, 10)->create()->each(function ($company) use ($ownerRole) {
            $company->load('user');
            /** @var \App\Entities\User $user */
            $user = $company->user;
            $user->userCompanyRole()->create([
                'company_id' => $company->id,
                'role_id' => $ownerRole->id,
            ]);
        });
    }
}
