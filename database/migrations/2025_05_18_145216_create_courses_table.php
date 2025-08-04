<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Teacher;
use App\Models\Subject;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->json('requirements')->nullable()->default(json_encode(['A Brain']));
            $table->foreignIdFor(Teacher::class);
            $table->foreignIdFor(Subject::class);
            $table->integer('lecturesCount');
            $table->integer('subscriptions');
            $table->string('image');
            $table->decimal('price', 10, 2)->default(10);
            $table->boolean('sparkies')->default(false);
            $table->boolean('sparkiesPrice')->default(0);
            $table->json('sources')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
