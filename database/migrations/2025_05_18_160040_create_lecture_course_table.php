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
        Schema::create('lecture_course', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lecture_id');
            $table->unsignedBigInteger('course_id');
            $table->timestamps();
            
            $table->foreign('lecture_id')->references('id')->on('lectures')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_course');
    }
};
