<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Str;


class UsersTableSeeder extends Seeder
{
    private const EMAIL = 'admin@master702.ru';
    private const PASSWORD = '123456';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 // Let's clear the users table first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');



        $admin = User::where('email', self::EMAIL)->first();

        if (is_null($admin)) {
            $admin = User::create([
                    'name' => 'admin',
                    'email' => self::EMAIL,
                    'password' =>  $password = Hash::make(PASSWORD)
                ]);
        }






        $faker = \Faker\Factory::create();

        // Let's make sure everyone has the same password and
        // let's hash it before the loop, or else our seeder
        // will be too slow.
        $password = Hash::make('123456');

        // And now let's generate a few dozen users for our app:
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => 'user_'.Str::random(10),
                'email' => Str::random(10).'@gmail.com',
                'password' => $password,
            ]);
        }
    }
}
