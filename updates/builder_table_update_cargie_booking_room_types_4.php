<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingRoomTypes4 extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_room_types', function($table)
        {
            $table->integer('sort_order')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_room_types', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
