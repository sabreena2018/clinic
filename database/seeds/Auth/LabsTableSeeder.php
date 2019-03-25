<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabsTableSeeder extends Seeder
{
    use DisableForeignKeys;


    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        $labs = [
            ['name' => 'Lab1'],
            ['name' => 'Lab2'],
            ['name' => 'Lab3'],
        ];

        DB::table('labs')->insert($labs);

        $this->enableForeignKeys();

    }
}