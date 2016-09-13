<?php namespace Cargie\Booking\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateSeasonDatesTable extends Migration
{
    public function up()
    {
        Schema::create('cargie_booking_season_dates', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('season_id')->nullable();
            $table->date('date')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cargie_booking_season_dates');
    }
}
