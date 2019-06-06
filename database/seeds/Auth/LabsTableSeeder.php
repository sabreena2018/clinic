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
            ['name' => 'Lab1','owner_id' => 4],
            ['name' => 'Lab2','owner_id' => 4],
            ['name' => 'Lab3','owner_id' => 4],
        ];

        DB::table('labs')->insert($labs);

        $this->enableForeignKeys();

    }
}