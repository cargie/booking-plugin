<?php namespace Cargie\Booking\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateRoomsTable extends Migration
{
    public function up()
    {
        Schema::create('cargie_booking_rooms', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('room_type_id')->nullable()->unsigned();
            $table->integer('visitor_cost_id')->unsigned();
            $table->string('name', 30);
            $table->boolean('is_enable')->default(1);
            $table->string('slug', 50)->unique();
            $table->integer('priority');
            $table->text('description');
            $table->decimal('rate', 10, 2);
            $table->integer('capacity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cargie_booking_rooms');
    }
}
