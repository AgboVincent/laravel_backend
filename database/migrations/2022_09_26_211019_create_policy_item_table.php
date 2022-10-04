<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolicyItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_policy_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('policy_id')->references('id')->on('new_policies');
            $table->boolean('is_covered')->nullable();
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'PolicyItemSeeder',
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
        Schema::dropIfExists('new_policy_items');
    }
}
