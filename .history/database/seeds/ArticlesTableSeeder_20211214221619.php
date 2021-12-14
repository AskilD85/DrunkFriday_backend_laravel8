<?php
namespace Database\Seeders;

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
                'title' => Str::random(10),
                'body' => Str::random(70),
                'category_id' => 1,
                'city_id' => 1,
                'user_id' => 1,
                'active' => 1,
            ]);
        }
    }
}
