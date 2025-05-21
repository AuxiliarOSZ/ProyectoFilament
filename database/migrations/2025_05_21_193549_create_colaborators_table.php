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
        Schema::create('colaborators', function (Blueprint $table) {
            $table->id();
            $table->string('document_type');
            $table->integer('document_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->timestamp('birth_date');
            $table->string('personal_email');
            $table->string('corporate_email')->nullable();
            $table->integer('mobile');
            $table->integer('phone')->nullable();
            $table->string('address');
            $table->string('residential_city');
            $table->string('education_level');
            $table->string('job_position');
            $table->timestamp('hire_date');
            $table->boolean('status');
            $table->string('eps');
            $table->string('arl');
            $table->text('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colaborators');
    }
};
