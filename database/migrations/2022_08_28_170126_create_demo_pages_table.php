<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemoPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_pages', function (Blueprint $table) {
            $table->id('pageId');
            $table->string('title');
            $table->string('slug');
            $table->text('content');
            $table->string('author');
            $table->string('image');
            $table->boolean('status');
            
            $table->integer('updatedBy')->nullable();
            $table->datetime('updatedAt')->nullable();
            $table->integer('createdBy')->nullable();
            $table->datetime('createdAt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_pages');
    }
}
