<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeFieldInAllContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_attachments', function (Blueprint $table){
            $table->string('type')->nullable()->after('created_by');
        });

        Schema::table('contract_comments', function (Blueprint $table){
            $table->string('type')->nullable()->after('created_by');
        });

        Schema::table('contract_notes', function (Blueprint $table){
            $table->string('type')->nullable()->after('created_by');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_attachments', function (Blueprint $table){
            $table->dropColumn('type');
        });

        Schema::table('contract_comments', function (Blueprint $table){
            $table->dropColumn('type');
        });

        Schema::table('contract_notes', function (Blueprint $table){
            $table->dropColumn('type');
        });
    }
}
