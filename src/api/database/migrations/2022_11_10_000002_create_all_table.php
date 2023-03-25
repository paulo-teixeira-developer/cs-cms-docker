<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        /**persons**/
        if (!Schema::hasTable('persons')) {
            Schema::create('persons', function (Blueprint $table) {
                //ESTRUTURA TABELA
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';

                //COLUNAS
                $table->id();
                $table->string('name',100);
                $table->string('last_name',100);
                $table->date('birth');
                $table->string('profession', 100);
                $table->string('biography', 2000);
                $table->unsignedBigInteger('file_id', false)->nullable();
                $table->timestamps();
            });
        }

        /**users**/
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                //ESTRUTURA TABELA
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';

                //COLUNAS
                $table->id();
                $table->unsignedBigInteger('person_id', false);
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->boolean('active')->default(true);
                $table->rememberToken();
                $table->timestamps();
            });
        }

        /**categories**/
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                //ESTRUTURA TABELA
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';

                //COLUNAS
                $table->id();
                $table->string('name',50)->unique();
                $table->timestamps();
            });
        }

        /**files **/
        if (!Schema::hasTable('files')) {
            Schema::create('files', function (Blueprint $table) {
                //ESTRUTURA TABELA
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';

                //COLUNAS
                $table->id();
                $table->string('name',100);
                $table->string('hash',100);
                $table->integer('size');
                $table->unsignedBigInteger('file_format_id',false);
                $table->unsignedBigInteger('file_path_id',false);
                $table->timestamps();
            });
        }

        /**file format **/
        if (!Schema::hasTable('file_formats')) {
            Schema::create('file_formats', function (Blueprint $table) {
                //ESTRUTURA file_formats
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';

                //COLUNAS
                $table->unsignedBigInteger('id', false)->primary();
                $table->string('name', 5);
            });
        }

        /**file path**/
        if (!Schema::hasTable('file_paths')) {
            Schema::create('file_paths', function (Blueprint $table) {
                //ESTRUTURA TABELA
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';

                //COLUNAS
                $table->unsignedBigInteger('id', false)->primary();
                $table->string('name', 20)->unique();
            });
        }

        /**posts**/
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                //ESTRUTURA TABELA
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';

                //COLUNAS
                $table->id();
                $table->string('title', 200);
                $table->string('slug', 200)->unique();
                $table->tinyText('summary');
                $table->mediumText('content');
                $table->boolean('published');
                $table->unsignedBigInteger('user_id', false);
                $table->unsignedBigInteger('category_id', false);
                $table->unsignedBigInteger('file_id', false);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('persons');
        Schema::dropIfExists('users');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('files');
        Schema::dropIfExists('file_formats');
        Schema::dropIfExists('file_paths');
        Schema::dropIfExists('posts');
    }
};
