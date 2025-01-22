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
        Schema::table('posts', function (Blueprint $table) {
            $table->String('thumbnail')->nullable();
            $table->string('title')->nullable();
            $table->string('color')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('published')->default(false);
            $table->foreignId('category_id')->nullable()->constrained()->cascadeOnDel()->after('navmenu_id');

            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
              'thumbnail',
              'title',
              'color',
              'tags',
              'published',
              'category_id',
            ]);
        });
    }
};
