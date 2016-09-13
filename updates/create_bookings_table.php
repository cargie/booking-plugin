<?php namespace Cargie\Booking\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('cargie_booking_bookings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('room_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->decimal('rate', 10, 2);
            $table->integer('adult');
            $table->integer('children');
            $table->string('status', 10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cargie_booking_bookings');
    }
}
