<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addsequencetocategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedInteger('sequence');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('sequence');
        });
        Schema::table('form_fields', function (Blueprint $table) {
            $table->unsignedInteger('sequence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('sequence');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sequence');
        });
        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropColumn('sequence');
        });
    }
}
