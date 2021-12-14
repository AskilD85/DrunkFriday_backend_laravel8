<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
     protected $batch;

    public function __construct()
    {
        $this->batch = DB::table('seeds')->max('batch') + 1;
    }
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(ArticlesTableSeeder::class);
    }

     public function call( $seeder, $silent = false, array $parameters = []) {
        if (DB::table('seeds')->where('seed','=',$seeder)->exists()) return false;

        parent::call($seeder, $silent, $parameters );

        DB::table('seeds')->insert([
            'seed'=>$seeder,
            'batch'=> $this->batch
        ]);
    }
}
