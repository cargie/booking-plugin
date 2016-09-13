<?php namespace Cargie\Booking\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateRoomTypeRatesTable extends Migration
{
    public function up()
    {
        Schema::create('cargie_booking_room_type_rates', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('room_type_id')->nullable();
            $table->decimal('rate', 10, 2)->nullable();
            $table->string('type', 1)->comment('P => Percentage, F => Fix')->nullable();
            $table->integer('season_id')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cargie_booking_room_type_rates');
    }
}
