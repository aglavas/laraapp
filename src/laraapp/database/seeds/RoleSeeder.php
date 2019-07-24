<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Spatie\Permission\Models\Role::create([
            'name' => 'admin'
        ]);

        \Spatie\Permission\Models\Role::create([
            'name' => 'guest'
        ]);

        \Spatie\Permission\Models\Role::create([
            'name' => 'owner'
        ]);
    }
}
