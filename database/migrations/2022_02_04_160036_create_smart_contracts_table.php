<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmartContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smart_contracts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('env_setting_id');
            $table->unsignedInteger('address_type_id');
            $table->unsignedInteger('currency_id');
            $table->string('name');
            $table->string('short_name');
            $table->string('token');
            $table->integer('decimals')->default(0)->comment('合約精度');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smart_contracts');
    }
}
