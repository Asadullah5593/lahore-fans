<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Starred ("featured") dealers are highlighted on the public page and float
     * to the top of their city group.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('is_active');

            // Listings order by featured first, then sort_order, within a city.
            $table->index(['is_featured', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropIndex(['is_featured', 'sort_order']);
            $table->dropColumn('is_featured');
        });
    }
};
