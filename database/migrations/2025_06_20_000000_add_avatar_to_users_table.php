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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('number');
            $table->integer('privileges')->default(3)->after('isBanned'); // 0=teacher, 1=semiadmin, 2=fulladmin, 3=student
            $table->unsignedBigInteger('teacher_id')->nullable()->after('privileges');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('privileges');
            $table->dropColumn('teacher_id');
        });
    }
}; 