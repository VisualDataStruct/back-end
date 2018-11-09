<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlgorithmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('algorithm', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('classification_id')->default(0);
            $table->string('name', '120')->unique();
            $table->text('pseudoCode');
            $table->text('CPlusCode')->nullable();
            $table->text('jsCode');
            $table->text('explain');
            $table->text('problems');
            $table->boolean('passed')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('algorithm');
    }
}
