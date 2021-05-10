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
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

        });
        // Schema::table('products', function (Blueprint $table) {
        //     $table->foreignId('created_by')
        //     ->nullable()
        //     ->constrained('users');

        //     $table->foreignId('category_id')
        //     ->nullable()
        //     ->constrained('categories');
        // });
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
