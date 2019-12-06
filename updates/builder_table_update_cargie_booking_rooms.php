<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingRooms extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_rooms', function($table)
        {
            $table->integer('nest_left')->nullable();
            $table->integer('nest_right')->nullable();
            $table->integer('nest_depth')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_rooms', function($table)
        {
            $table->dropColumn('nest_left');
            $table->dropColumn('nest_right');
            $table->dropColumn('nest_depth');
        });
    }
}
