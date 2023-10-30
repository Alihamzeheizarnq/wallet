<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('currency_key');
            $table->double('rate');
            $table->string('rate_currency_key');
            $table->timestamps();

            $table->foreign('currency_key')
                ->references('key')
                ->on('currencies')
                ->onUpdate('cascade');

            $table->foreign('rate_currency_key')
                ->references('key')
                ->on('currencies')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
