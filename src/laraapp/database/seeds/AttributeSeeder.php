<?php

use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Entities\Attribute::create([
            'name' => 'color'
        ]);

        \App\Entities\Attribute::create([
            'name' => 'size'
        ]);

        \App\Entities\Attribute::create([
            'name' => 'volume'
        ]);
    }
}
