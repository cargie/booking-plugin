<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingRooms4 extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_rooms', function($table)
        {
            $table->integer('priority')->nullable()->default(0)->change();
            $table->dropColumn('sort_order');
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_rooms', function($table)
        {
            $table->integer('priority')->nullable(false)->default(null)->change();
            $table->string('sort_order', 10)->nullable();
        });
    }
}
