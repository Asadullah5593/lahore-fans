<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The city centre is no longer entered by hand in the admin panel — it is
     * derived from the coordinates of the city's own outlets. Existing values
     * are kept and still take precedence; only new cities leave them empty.
     */
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->decimal('lat', 10, 7)->nullable()->change();
            $table->decimal('lng', 10, 7)->nullable()->change();
        });
    }

    public function down(): void
    {
        // Any city left without a centre gets 0,0 so the columns can go back to
        // NOT NULL without the rollback failing.
        DB::table('cities')->whereNull('lat')->update(['lat' => 0, 'lng' => 0]);

        Schema::table('cities', function (Blueprint $table) {
            $table->decimal('lat', 10, 7)->nullable(false)->change();
            $table->decimal('lng', 10, 7)->nullable(false)->change();
        });
    }
};
