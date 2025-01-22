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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->String('name')->nullable();
            $table->foreignId('navmenu_id')-> constrained();
            $table->String('content')->nullable();
            $table->string('image')->nullable(); 
            $table->enum('status', ['Active', 'InActive'])
            ->default('Active')
            ->comment('Active/Inactive status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
