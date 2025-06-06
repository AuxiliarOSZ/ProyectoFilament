<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('colaborators', function (Blueprint $table) {
            $table->renameColumn('status', 'old_status');
        });

        Schema::table('colaborators', function (Blueprint $table) {
            $table->boolean('status')->default(true);
        });

        Schema::table('colaborators', function (Blueprint $table) {
            $table->dropColumn('old_status');
        });
    }

    public function down(): void
    {
        Schema::table('colaborators', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('colaborators', function (Blueprint $table) {
            $table->enum('status', ['activo', 'inactivo'])->default('activo');
        });
    }
};
