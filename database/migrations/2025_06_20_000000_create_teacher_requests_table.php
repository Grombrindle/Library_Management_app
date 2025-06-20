<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->enum('action_type', ['add', 'edit', 'delete']);
            $table->string('target_type'); // course, lecture, etc.
            $table->unsignedBigInteger('target_id')->nullable(); // nullable for add actions
            $table->json('payload'); // data to be applied
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
            $table->text('admin_response')->nullable(); // Optional reason for decline
            $table->foreignId('admin_id')->nullable()->constrained('admins'); // Admin who processed the request
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
        Schema::dropIfExists('teacher_requests');
    }
}; 