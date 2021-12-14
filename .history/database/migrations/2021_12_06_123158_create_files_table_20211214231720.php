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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('files');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
