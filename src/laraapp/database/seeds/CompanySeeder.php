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
        \App\Entities\Company::create([
           'name' => 'Ime',
           'location' => 'Lokacija',
           'user_id' => 1,
        ]);
    }
}
