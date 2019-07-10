<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('project_status_id')->unsigned()->index()->nullable();
            $table->bigInteger('payment_method_id')->unsigned()->index()->nullable();
            $table->bigInteger('payment_status_id')->unsigned()->index()->nullable();
            $table->string('title');
            $table->string('progress')->default('0%');
            $table->text('brief');
            $table->string('file_location')->nullable();
            $table->date('deadline');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
