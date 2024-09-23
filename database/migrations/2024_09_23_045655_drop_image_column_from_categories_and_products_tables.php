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
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        // Dropping 'image' column from 'products' table
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image')->nullable();
        });

        // Re-adding 'image' column to 'products' table
        Schema::table('products', function (Blueprint $table) {
            $table->string('image')->nullable();
        });
    }
};
