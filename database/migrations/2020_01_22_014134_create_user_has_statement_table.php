<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHasStatementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_has_statement', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('statement_id');
            $table->integer('classroom_id');
            $table->integer('user_id');
            $table->boolean('sign')->default(0);
            $table->scholar_period();
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
        Schema::dropIfExists('user_has_statement');
    }
}
