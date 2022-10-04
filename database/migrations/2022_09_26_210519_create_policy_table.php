<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolicyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->float('amount', 8, 2);
            $table->string('payment_duration');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'PolicySeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_policies');
    }
}
