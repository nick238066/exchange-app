<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberAddressTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_address_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('address_type_id');
            $table->unsignedInteger('address_id');
            $table->unsignedInteger('contract_id');
            $table->unsignedInteger('currency_id');
            $table->decimal('balance', 15, 6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_address_tokens');
    }
}
