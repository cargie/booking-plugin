<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingRoomTypes3 extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_room_types', function($table)
        {
            $table->boolean('is_available')->default(1);
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_room_types', function($table)
        {
            $table->dropColumn('is_available');
        });
    }
}
