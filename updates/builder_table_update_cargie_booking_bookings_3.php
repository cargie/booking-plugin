<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingBookings3 extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_bookings', function($table)
        {
            $table->text('note')->after('payment_status')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_bookings', function($table)
        {
            $table->dropColumn('note');
        });
    }
}
