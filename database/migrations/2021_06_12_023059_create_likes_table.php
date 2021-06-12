<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $likeEntities = config('app.like_entities');

            $table->id();
            $table->enum('entity', $likeEntities)->default('videos');
            $table->unsignedBigInteger('entity_id');
            $table->foreignId('user_id');
            $table->timestamps();

            $table->index(['entity', 'entity_id']);
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
        Schema::dropIfExists('likes');
    }
}
