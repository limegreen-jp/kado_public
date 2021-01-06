<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('client_name')->nullable($value = true);
            $table->string('project_name');
            $table->foreignId('skill_id')->nullable($value = true);
            $table->foreignId('level_id')->nullable($value = true);
            $table->foreignId('user_id')->nullable($value = true)->comment('担当者');
            $table->longText('description')->nullable($value = true);
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
        Schema::dropIfExists('projects');
    }
}
