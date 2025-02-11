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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->default()->nullable();
            $table->foreignId('state_id')->constrained('states')->default()->nullable();
            $table->foreignId('city_id')->constrained('cities')->default()->nullable();
            $table->foreignId('department_id')->constrained('departments')->default()->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name');
            $table->string('address');
            $table->char('zip_code');
            $table->date('date_of_birth');
            $table->date('date_hired');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
