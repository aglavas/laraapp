<?php

use Illuminate\Database\Seeder;

class UserAuthorizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = \Spatie\Permission\Models\Role::where('name', '!=', 'owner')->get();
        $permissions = \Spatie\Permission\Models\Permission::all();

        $usersCollection = \App\Entities\User::with('userCompanyRole')->get();
        $modifiedUsersCollection = $usersCollection->keyBy('id');

        $userCompanyRoleCollection = $usersCollection->pluck('userCompanyRole')->flatten();

        $ownerMappingCollection = $userCompanyRoleCollection->map(function ($item) {
            return ['user_id' => $item['user_id'], 'company_id' => $item['company_id']];
        })->keyBy('company_id');

        $companies = \App\Entities\Company::all();

        $companies->each(function ($company) use ($roles, $ownerMappingCollection, $modifiedUsersCollection, $permissions) {
            $ownerId = $ownerMappingCollection->get($company->id)['user_id'];
            $usersWithoutOwner = $modifiedUsersCollection->except($ownerId);
            $user = $usersWithoutOwner->random();
            $role = $roles->random();
            $company->userCompanyRole()->create([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);

            $user = $usersWithoutOwner->random();
            $permission = $permissions->random();
            $company->userCompanyPermission()->create([
                'user_id' => $user->id,
                'permission_id' => $permission->id,
            ]);
        });
    }
}
