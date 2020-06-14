<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableConstraintToMyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_info', function (Blueprint $table) {
            $table->string("city")->nullable()->change();
            $table->integer("zip_code")->nullable()->change();
            $table->text("address")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_info', function (Blueprint $table) {
            $table->string("city")->nullable(false)->change();
            $table->integer("zip_code")->nullable(false)->change();
            $table->text("address")->nullable(false)->change();
        });
    }
}
