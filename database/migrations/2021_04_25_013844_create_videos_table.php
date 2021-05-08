<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('url');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['youtube', 'vimeo'])->default('youtube');
            $table->boolean('is_published')->default(0);
            $table->integer('like_count')->unsigned()->default(0);
            $table->integer('abuse_count')->unsigned()->default(0);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
