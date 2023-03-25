<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->foreign('file_id')->references('id')->on('files');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('person_id')->references('id')->on('persons');
        });

        Schema::table('files', function (Blueprint $table) {
            $table->foreign('file_format_id')->references('id')->on('file_formats');
            $table->foreign('file_path_id')->references('id')->on('file_paths');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('file_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropForeign(['file_id']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
        });
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['file_format_id']);
            $table->dropForeign(['file_path_id']);
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['file_id']);
        });
    }
};
