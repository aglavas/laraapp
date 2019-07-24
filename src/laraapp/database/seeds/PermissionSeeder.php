<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Spatie\Permission\Models\Permission::create([
            'name' => 'view'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'update'
        ]);
    }
}
