<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingBookings2 extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_bookings', function($table)
        {
            $table->string('payment_status')->after('status')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_bookings', function($table)
        {
            $table->dropColumn('payment_status');
        });
    }
}
