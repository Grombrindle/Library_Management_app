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
        Schema::create('lectures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_360')->nullable();
            $table->string('file_720')->nullable();
            $table->string('file_1080')->nullable();
            $table->string('file_pdf')->nullable();
            $table->string('description')->nullable();  //Maybe I'll add more for the other languages
            $table->string('image');
            $table->float('duration')->nullable();
            $table->integer('pages')->nullable();
            $table->boolean('type');    //0 is PDF 1 is video
            $table->integer('views')->default(0);
            $table->foreignIdFor(App\Models\Quiz::class)->nullable();
            $table->foreignIdFor(App\Models\Course::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};
