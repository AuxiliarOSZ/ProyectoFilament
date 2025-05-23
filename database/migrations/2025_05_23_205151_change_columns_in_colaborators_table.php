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
            $table->string('document_type', 15)->change();
            $table->string('document_number', 20)->index()->change();
            $table->string('first_name', 100)->change();
            $table->string('last_name', 100)->change();
            $table->string('gender', 20)->change();
            $table->date('birth_date')->change();
            $table->string('personal_email')->unique()->change();
            $table->string('corporate_email')->nullable()->unique()->change();
            $table->string('mobile', 15)->change();
            $table->string('phone', 15)->nullable()->change();
            $table->string('address', 150)->change();
            $table->string('residential_city', 100)->change();
            $table->string('education_level', 100)->change();
            $table->string('job_position', 100)->change();
            $table->date('hire_date')->change();
            $table->enum('status', ['activo', 'inactivo'])->change();
            $table->string('eps', 100)->change();
            $table->string('arl', 100)->change();
            $table->longText('notes')->change();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colaborators', function (Blueprint $table) {
            $table->dropColumn('document_type');
            $table->dropColumn('document_number');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('gender');
            $table->dropColumn('birth_date');
            $table->dropColumn('personal_email');
            $table->dropColumn('corporate_email');
            $table->dropColumn('mobile');
            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropColumn('residential_city');
            $table->dropColumn('education_level');
            $table->dropColumn('job_position');
            $table->dropColumn('hire_date');
            $table->dropColumn('status');
            $table->dropColumn('eps');
            $table->dropColumn('arl');
            $table->dropColumn('notes');
            $table->dropColumn('deleted_at');
        });
    }
};
