<?php namespace Cargie\Booking\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateBookingDatesTable extends Migration
{
    public function up()
    {
        Schema::create('cargie_booking_booking_dates', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('booking_id')->unsigned();
            $table->date('date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cargie_booking_booking_dates');
    }
}
