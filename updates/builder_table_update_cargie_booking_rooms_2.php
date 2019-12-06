<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingRooms2 extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_rooms', function($table)
        {
            $table->integer('sort_order')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_rooms', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
