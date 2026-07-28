<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The dealer lists record who runs each shop. Nullable because two of the
     * three source lists (Lahore and Karachi) carry no owner column at all.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('owner_name')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('owner_name');
        });
    }
};
