<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\UserAdmin;
class UserAdminSeeder extends Seeder
{
    public function run()
    {
        UserAdmin::factory(5)->create();
    }
}
