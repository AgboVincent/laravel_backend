<?php

use App\Models\Guarantee;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPolicyIdToGuaranteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guarantees', function (Blueprint $table) {
            $table->foreignId('policy_id')->after('guarantee_type_id')->nullable()->constrained();
            $table->unique(['policy_id', 'guarantee_type_id']);
            $table->dropColumn('claim_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guarantees', function (Blueprint $table) {
            $table->unsignedBigInteger('claim_id')->after('guarantee_type_id');
            $table->dropUnique(['policy_id', 'guarantee_type_id']);
            $table->dropConstrainedForeignId('policy_id');
        });
    }
}
