<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingRooms3 extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_rooms', function($table)
        {
            $table->string('sort_order', 10)->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_rooms', function($table)
        {
            $table->integer('sort_order')->nullable()->unsigned(false)->default(null)->change();
        });
    }
}
