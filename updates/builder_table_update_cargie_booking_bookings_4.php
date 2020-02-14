<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingBookings4 extends Migration
{
    public function up()
    {
        Schema::table('cargie_booking_bookings', function($table)
        {
            $table->integer('room_id')->change();
            $table->decimal('rate', 10, 2)->nullable()->change();
            $table->integer('adult')->nullable()->change();
            $table->integer('children')->nullable()->change();
            $table->string('payment_status', 191)->nullable(false)->default('null')->change();
        });
    }
    
    public function down()
    {
        Schema::table('cargie_booking_bookings', function($table)
        {
            $table->integer('room_id')->nullable(false)->change();
            $table->decimal('rate', 10, 2)->nullable(false)->change();
            $table->integer('adult')->nullable(false)->change();
            $table->integer('children')->nullable(false)->change();
            $table->string('payment_status', 191)->nullable()->default(null)->change();
        });
    }
}
