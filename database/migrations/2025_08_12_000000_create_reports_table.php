<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reporter_id'); // The user who submitted the report
            $table->unsignedBigInteger('user_id'); // The user who was reported
            $table->unsignedBigInteger('handled_by_id')->nullable(); // The user who worked on it (admin/mod)
            $table->text('lecture_rating_id')->nullable();
            $table->text('lecture_comment')->nullable();
            $table->text('teacher_rating_id')->nullable();
            $table->text('teacher_comment')->nullable();
            $table->text('course_rating_id')->nullable();
            $table->text('course_comment')->nullable();
            $table->text('resource_rating_id')->nullable();
            $table->text('resource_comment')->nullable();
            $table->text('message')->nullable(); // Free text reason
            $table->json('reasons'); // Array of enums (must have at least one)
            $table->enum('status', allowed: ['PENDING','IGNORED', 'WARNED', 'BANNED'])->default('PENDING');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('handled_by_id')->references('id')->on('admins')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
