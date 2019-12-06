<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingBookingDates extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_booking_dates', function($table)
        {
            $table->integer('booking_room_id')->nullable()->unsigned();
            $table->integer('booking_id')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_booking_dates', function($table)
        {
            $table->dropColumn('booking_room_id');
            $table->integer('booking_id')->nullable(false)->change();
        });
    }
}
