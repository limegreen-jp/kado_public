<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('month_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->string('date');
            $table->foreignId('term_id')->nullable($value = true)->comment('期');
            $table->float('working_time')->nullable($value = true);
            $table->float('price')->nullable($value = true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('month_prices');
    }
}
