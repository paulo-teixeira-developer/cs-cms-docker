<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\File;
use App\Models\FileFormat;
use App\Models\FilePath;
use App\Models\Person;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /** independentes **/

        FilePath::insert([
            ['id' => 1, 'name' => 'private/user/img'],
            ['id' => 2, 'name' => 'private/user/doc'],
            ['id' => 3, 'name' => 'public/img'],
            ['id' => 4, 'name' => 'public/audio'],
            ['id' => 5, 'name' => 'public/video'],
        ]);

        FileFormat::insert([
            ['id' => 1, 'name' => 'jpg'],
            ['id' => 2, 'name' => 'jpeg'],
            ['id' => 3, 'name' => 'png'],
            ['id' => 4, 'name' => 'mp3'],
            ['id' => 5, 'name' => 'm4a'],
            ['id' => 6, 'name' => 'wav'],
            ['id' => 7, 'name' => 'mp4'],
            ['id' => 8, 'name' => 'svg'],
        ]);

        Category::create(['name' => 'tecnologia']);

        $person = Person::create([
            "name" => "Paulo Ananias",
            "last_name" => "Teixeira",
            "birth" => "19940101",
            "profession" => "Analista e Desenvolvedor de Sistemas",
            "biography" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
        ]);

        $person->user()->create([
            "email" => "pauloteixeira.dev@outlook.com",
            "password" => "123456789",
        ]);
    }
}
