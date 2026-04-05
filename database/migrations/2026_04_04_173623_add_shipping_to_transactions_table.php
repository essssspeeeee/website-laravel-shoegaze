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
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'shipping_method')) {
                $table->string('shipping_method')->default('reguler');
            }
            if (!Schema::hasColumn('transactions', 'shipping_cost')) {
                $table->integer('shipping_cost')->default(8000);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['shipping_method', 'shipping_cost']);
        });
    }
};
