<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingRoomTypes2 extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_room_types', function($table)
        {
            $table->text('short_description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_room_types', function($table)
        {
            $table->dropColumn('short_description');
        });
    }
}
