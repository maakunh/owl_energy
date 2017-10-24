<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwlSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owl_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->integer('addr')->nullable();
            $table->double('tarif_base',15,2)->nullable();
            $table->double('tarif_1',15,2)->nullable();
            $table->double('tarif_2',15,2)->nullable();
            $table->double('tarif_3',15,2)->nullable();
            $table->double('fuel_adj',15,2)->nullable();
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
        Schema::dropIfExists('owl_settings');
    }
}
