<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('level')->nullable()->default(null);
            $table->string('color');
            $table->string('type');
            $table->time('min_hour');
            $table->time('max_hour');
            $table->boolean('require_justification')->default(false);
            $table->integer('scholarperiod_id');
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
        Schema::dropIfExists('attendance_types');
    }
}
