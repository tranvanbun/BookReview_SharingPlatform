<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('title');
            $table->string('author');
            $table->text('description');
            $table->string('cover_img');
            $table->string('link');
            $table->unsignedBigInteger('genre_id')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('favorites')->default(0);
            $table->foreign('genre_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
