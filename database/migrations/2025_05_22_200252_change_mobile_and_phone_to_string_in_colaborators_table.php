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
        Schema::table('colaborators', function (Blueprint $table) {
            $table->string('mobile')->change();
            $table->string('phone')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colaborators', function (Blueprint $table) {
            $table->integer('mobile')->change();
            $table->integer('phone')->change();
        });
    }
};
