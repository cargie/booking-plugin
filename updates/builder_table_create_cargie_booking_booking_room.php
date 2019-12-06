<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCargieBookingBookingRoom extends Migration
{
    public function up()
    {
        Schema::create('cargie_booking_booking_room', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('booking_id')->unsigned();
            $table->integer('room_id')->unsigned();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('cargie_booking_booking_room');
    }
}
