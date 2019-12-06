<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingRoomTypes extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_room_types', function($table)
        {
            $table->text('meta')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_room_types', function($table)
        {
            $table->dropColumn('meta');
        });
    }
}
