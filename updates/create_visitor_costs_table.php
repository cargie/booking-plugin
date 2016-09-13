<?php namespace Cargie\Booking\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateVisitorCostsTable extends Migration
{
    public function up()
    {
        Schema::create('cargie_booking_visitor_costs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 30);
            $table->string('slug', 50)->unique();
            $table->text('adult_rates');
            $table->text('children_rates')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cargie_booking_visitor_costs');
    }
}
