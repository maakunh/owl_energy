<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnergyHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('addr');
            $table->integer('year');
            $table->integer('month');
            $table->integer('day');
            $table->integer('hour');
            $table->integer('min');
            $table->integer('ch1_amps_avg');
            $table->integer('ch1_kw_avg');
            $table->decimal('ch1_kwh', 5, 2); //KWH追加
            $table->integer('ghg');
            $table->integer('cost');
            $table->integer('ch1_amps_min');
            $table->integer('ch1_amps_max');
            $table->integer('ch1_kw_min');
            $table->integer('ch1_kw_max');
            $table->string('dt');
            $table->bigInteger('timestamp')->unique();
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('energy_histories');
    }
}
