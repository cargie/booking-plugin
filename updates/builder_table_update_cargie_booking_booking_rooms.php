<?php namespace Cargie\Booking\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCargieBookingBookingRooms extends Migration
{
    public function up()
    {
        Schema::rename('cargie_booking_booking_room', 'cargie_booking_booking_rooms');
    }
    
    public function down()
    {
        Schema::rename('cargie_booking_booking_rooms', 'cargie_booking_booking_room');
    }
}
