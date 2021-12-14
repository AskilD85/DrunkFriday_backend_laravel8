<?php

use Illuminate\Database\Seeder;
use App\Article;
class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Удалим имеющиеся в таблице данные
        Article::truncate();

        $faker = \Faker\Factory::create();

        // А теперь давайте создадим 50 статей в нашей таблице
        for ($i = 0; $i < 50; $i++) {
            Article::create([
                'title' => $faker->sentence,
                'body' => $faker->paragraph,
            ]);
        }
    }
}
