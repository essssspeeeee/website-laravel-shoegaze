<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSizeToCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('size')->nullable()->after('product_id');
            $table->dropForeign(['user_id']);
            $table->dropForeign(['product_id']);
            $table->dropUnique(['user_id', 'product_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique(['user_id', 'product_id', 'size']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'product_id', 'size']);
            $table->dropColumn('size');
            $table->unique(['user_id', 'product_id']);
        });
    }
}
