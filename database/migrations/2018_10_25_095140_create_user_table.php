<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->string('id', '120')->unique()->comment('short uuid');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('realName')->comment('People\'s real name');
            $table->string('email', '120')->unique();
            $table->string('github')->nullable();
            $table->string('phone')->nullable();
            $table->integer('contribution')->default(0);
            $table->timestamps();

            $table->primary('id');
        });

        \Illuminate\Support\Facades\DB::table('user')->insert([
            'id' => '1',
            'username' => 'administrator',
            'password' => app('hash')->make(\App\Helper::sha256('admin')),
            'realName' => '管理员',
            'email' => 'admin@VDS.com',
            'contribution' => 0,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
