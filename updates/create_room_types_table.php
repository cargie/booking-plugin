<?php namespace Cargie\Booking\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateRoomTypesTable extends Migration
{
    public function up()
    {
        Schema::create('cargie_booking_room_types', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 30);
            $table->string('slug', 50)->unique();
            $table->boolean('is_enable')->default(1);
            $table->integer('visitor_cost_id')->unsigned();
            $table->text('description');
            $table->decimal('rate', 10, 2);
            $table->integer('capacity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cargie_booking_room_types');
    }
}
