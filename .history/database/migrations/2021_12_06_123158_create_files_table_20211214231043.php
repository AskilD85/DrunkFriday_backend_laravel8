<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('Наименование файла');
            $table->string('type')->comment('тип файла');
            $table->string('model_type')->comment('тип модели');
            $table->string('collection_name')->comment('имя коллекции');
            $table->string('file_name')->comment('имя загружаемого файла');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('size');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('files');
    }
}
