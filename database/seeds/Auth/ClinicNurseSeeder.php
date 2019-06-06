<?php

use App\Models\Auth\User;
use Illuminate\Database\Seeder;

/**
 * Class ClinicTableSeeder.
 */
class ClinicNurseSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        $clinicNurse = [
            ['clinic_id' => 4,'nurse_id' => 6],
        ];

        DB::table('clinic_nurse')->insert($clinicNurse);

        $this->enableForeignKeys();
    }
}
