<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimFinancialMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_financial_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained();
            $table->string('nature');
            $table->string('issuer')->nullable();
            $table->string('recipient')->nullable();
            $table->json('guarantees')->nullable();
            $table->decimal('amount');
            $table->string('payment_method')->nullable();
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
        Schema::dropIfExists('claim_financial_movements');
    }
}
