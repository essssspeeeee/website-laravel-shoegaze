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
            if (! Schema::hasColumn('transactions', 'shipping_method')) {
                $table->string('shipping_method')->nullable()->default('reguler');
            }

            if (! Schema::hasColumn('transactions', 'shipping_cost')) {
                $table->integer('shipping_cost')->nullable()->default(0);
            }

            if (! Schema::hasColumn('transactions', 'selected_address_name')) {
                $table->string('selected_address_name')->nullable();
            }

            if (! Schema::hasColumn('transactions', 'selected_address_phone')) {
                $table->string('selected_address_phone')->nullable();
            }

            if (! Schema::hasColumn('transactions', 'selected_address_jalan')) {
                $table->string('selected_address_jalan')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'shipping_method')) {
                $table->dropColumn('shipping_method');
            }

            if (Schema::hasColumn('transactions', 'shipping_cost')) {
                $table->dropColumn('shipping_cost');
            }

            if (Schema::hasColumn('transactions', 'selected_address_name')) {
                $table->dropColumn('selected_address_name');
            }

            if (Schema::hasColumn('transactions', 'selected_address_phone')) {
                $table->dropColumn('selected_address_phone');
            }

            if (Schema::hasColumn('transactions', 'selected_address_jalan')) {
                $table->dropColumn('selected_address_jalan');
            }
        });
    }
};
