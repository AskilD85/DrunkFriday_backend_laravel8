<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
     Schema::create('articles', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('title');
        $table->text('body');
        $table->integer('active')->default('0');
        $table->integer('category_id');
        $table->integer('user_id');
        $table->string('type')->default('1');
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
        // DB::statement('SET FOREIGN_KEY_CHECKS = 0');
	    Schema::dropIfExists('articles');
	    // DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
