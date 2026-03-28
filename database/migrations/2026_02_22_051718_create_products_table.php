<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 12, 2); // Menggunakan decimal lebih aman untuk uang
        $table->json('stock')->nullable(); // Ini untuk simpan stok per ukuran (39, 40, 41, dst)
        $table->json('images')->nullable(); // Pakai 'images' (jamak) sesuai permintaan Controller kamu
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
