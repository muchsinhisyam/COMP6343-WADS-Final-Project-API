<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableConstraintToMyTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_info', function (Blueprint $table) {
            $table->string("first_name")->nullable()->change();
            $table->string("last_name")->nullable()->change();
            $table->integer("phone")->nullable()->change();
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
            $table->string("first_name")->nullable(false)->change();
            $table->string("last_name")->nullable(false)->change();
            $table->integer("phone")->nullable(false)->change();
        });
    }
}
