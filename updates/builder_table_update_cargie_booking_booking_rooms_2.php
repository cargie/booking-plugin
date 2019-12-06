<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingBookingRooms2 extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_booking_rooms', function($table)
        {
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_booking_rooms', function($table)
        {
            $table->dropColumn('start_at');
            $table->dropColumn('end_at');
        });
    }
}
