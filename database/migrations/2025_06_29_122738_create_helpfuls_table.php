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
        Schema::create('helpfuls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lecture_rating_id')->nullable()->constrained('lecture_rating')->onDelete('cascade');
            $table->foreignId('course_rating_id')->nullable()->constrained('course_rating')->onDelete('cascade');
            $table->foreignId('teacher_rating_id')->nullable()->constrained('teacher_ratings')->onDelete('cascade');
            $table->foreignId('resource_rating_id')->nullable()->constrained('resources_ratings')->onDelete('cascade');
            $table->boolean('isHelpful');
            $table->timestamps();

            // Ensure one user can only have one helpful record per rating
            $table->unique(['user_id', 'lecture_rating_id'], 'helpfuls_user_lecture_unique');
            $table->unique(['user_id', 'course_rating_id'], 'helpfuls_user_course_unique');
            $table->unique(['user_id', 'teacher_rating_id'], 'helpfuls_user_teacher_unique');
            $table->unique(['user_id', 'resource_rating_id'], 'helpfuls_user_resource_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('helpfuls');
    }
};
