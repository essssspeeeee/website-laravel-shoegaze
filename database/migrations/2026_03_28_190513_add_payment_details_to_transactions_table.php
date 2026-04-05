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
        if (! Schema::hasColumn('transactions', 'payment_method')) {
            $table->string('payment_method')->default('qris'); 
        }
        if (! Schema::hasColumn('transactions', 'payment_proof')) {
            $table->string('payment_proof')->nullable(); 
        }
        if (! Schema::hasColumn('transactions', 'status')) {
            $table->string('status')->default('pending');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
