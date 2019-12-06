<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingBookingRooms3 extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_booking_rooms', function($table)
        {
            $table->integer('children')->nullable()->unsigned();
            $table->integer('adult')->nullable()->unsigned();
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_booking_rooms', function($table)
        {
            $table->dropColumn('children');
            $table->dropColumn('adult');
        });
    }
}
