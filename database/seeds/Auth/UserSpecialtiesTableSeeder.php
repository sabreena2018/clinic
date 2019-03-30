<?php

use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class AppointmentTableSeeder.
 */
class UserSpecialtiesTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {

       DB::table('user_specialties')->insert(
            [
                'user_id' => 2,
                'specialties_id' => 8,
            ]
        );

        DB::table('user_specialties')->insert(
            [
                'user_id' => 2,
                'specialties_id' => 10,
            ]
        );

        DB::table('user_specialties')->insert(
            [
                'user_id' => 8,
                'specialties_id' => 10,
            ]
        );

        DB::table('user_specialties')->insert(
            [
                'user_id' => 8,
                'specialties_id' => 9,
            ]
        );

        DB::table('user_specialties')->insert(
            [
                'user_id' => 8,
                'specialties_id' => 7,
            ]
        );


    }
}
