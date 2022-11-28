<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk')->nullable();
            $table->string('harga_produk')->nullable();
            $table->string('foto_produk')->nullable();
            $table->integer('stok_produk')->nullable()->default(0);
            $table->text('deskripsi_produk')->nullable();
            $table->char('status_produk', 1)->nullable(); //1=aktif,0=non active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
