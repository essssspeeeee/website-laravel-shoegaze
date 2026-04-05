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
            if (! Schema::hasColumn('transactions', 'recipient_name')) {
                $table->string('recipient_name')->nullable();
            }
            if (! Schema::hasColumn('transactions', 'phone_number')) {
                $table->string('phone_number')->nullable();
            }
            if (! Schema::hasColumn('transactions', 'full_address')) {
                $table->string('full_address')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'recipient_name')) {
                $table->dropColumn('recipient_name');
            }
            if (Schema::hasColumn('transactions', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('transactions', 'full_address')) {
                $table->dropColumn('full_address');
            }
        });
    }
};
