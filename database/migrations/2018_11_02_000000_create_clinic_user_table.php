<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateClinicUserTable.
 */
class CreateClinicUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('reservation_id')->index();
            $table->unsignedInteger('clinic_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('doctor_id');
            $table->unsignedInteger('specialties_id');
            $table->unsignedInteger('country_id');
            $table->string('city');
            $table->date('appointment');
            $table->time('time')->nullable();
            $table->string('Tperiod');
            $table->string('serviceL');


//            $table->foreign('clinic_id')->references('id')->on('clinics');
//            $table->foreign('user_id')->references('id')->on('users');
//            $table->foreign('doctor_id')->references('id')->on('users');
//            $table->foreign('specialties_id')->references('id')->on('users');
//            $table->foreign('country_id')->references('id')->on('countries');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clinic_user');
    }
}
