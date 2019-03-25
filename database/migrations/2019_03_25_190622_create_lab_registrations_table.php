<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('reservation_id')->index();
            $table->unsignedInteger('lab_id')->index();
            $table->date('appointment');
            $table->string('Tperiod');
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations');
            $table->foreign('lab_id')->references('id')->on('labs');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lab_registrations');
    }
}
