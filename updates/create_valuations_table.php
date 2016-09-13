<?php namespace Cargie\Booking\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateValuationsTable extends Migration
{
    public function up()
    {
        Schema::create('cargie_booking_valuations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // $table->integer('room_id')->unsigned()->nullable();
            $table->integer('valuable_id')->nullable();
            $table->string('valuable_type')->nullable();
            $table->integer('season_id')->unsigned()->nullable();
            $table->string('type', 2)->comment('FO => For, FR => From, TO => Together');
            $table->string('days', 10)->comment('
                FO => integer,
                FR => array,
                TO => integer
            ');
            $table->decimal('rate', 10, 2);
            $table->string('rate_type', 1)->comment('F => Fix, P => Percentage');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cargie_booking_valuations');
    }
}
