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
        Schema::create('segments', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->text('description_en')->nullable();
            $table->unsignedBigInteger('course_category_id')->index();
            $table->unsignedBigInteger('instructor_id')->index();
            $table->integer('duration')->nullable();
            $table->integer('lesson')->nullable();
            $table->string('image')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segments');
    }
};
