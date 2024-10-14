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
        Schema::create('progress_alls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('course_id')->index();
            $table->unsignedBigInteger('lesson_id')->index();
            $table->unsignedBigInteger('material_id')->index();
            $table->integer('progress_percentage')->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamps();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_alls');
    }
};
