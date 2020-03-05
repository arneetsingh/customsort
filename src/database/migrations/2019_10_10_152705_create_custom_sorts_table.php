<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomSortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_sorts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sortable_id');
            $table->string('sortable_type');
            $table->integer('priority');

            $table->unique(['sortable_id','sortable_type','priority']);
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
        Schema::dropIfExists('custom_sorts');
    }
}
