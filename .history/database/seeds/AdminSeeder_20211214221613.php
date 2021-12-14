
<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    private const EMAIL = 'admin@master702.ru';
    private const PASSWORD = '123456';

    /**
     * Сеим администратора
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('email', self::EMAIL)->first();

        if (is_null($admin)) {
            $admin = User::factory()
                ->create([
                    'email' => self::EMAIL,
                    'password' => self::PASSWORD,
                ]);
        }
/*
        $userRole = UserRole::where('user_id', $admin->id)
            ->where('role_id', Role::administrator()->id)
            ->first();

        if (is_null($userRole)) {
            UserRole::factory()->create([
                'user_id' => $admin->id,
                'role_id' => Role::administrator()->id
            ]);
        }*/
    }
}
