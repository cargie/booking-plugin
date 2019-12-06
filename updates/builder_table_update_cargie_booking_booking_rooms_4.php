<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingBookingRooms4 extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_booking_rooms', function($table)
        {
            $table->integer('booking_id')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_booking_rooms', function($table)
        {
            $table->integer('booking_id')->nullable(false)->change();
        });
    }
}
