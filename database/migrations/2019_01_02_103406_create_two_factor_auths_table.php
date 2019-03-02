<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwoFactorAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('two_factor_auths', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('user_id');
            $table->boolean('google2fa_enable')->default(false);
            $table->string('google2fa_secret')->nullable();
            $table->timestamps();
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('two_factor_auths');
    }
}
