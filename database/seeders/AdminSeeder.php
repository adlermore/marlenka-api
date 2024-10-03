<?php

namespace Database\Seeders;

use App\Models\AdminUsers;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "name"=>"Admin",
                "email"=>"admin@example.com",
                "password"=>"asdasd"
            ]
        ];
        $adminUsers = AdminUsers::pluck("user_id")->toArray();
        User::whereIn("id",$adminUsers)->delete();
        DB::table('admin_users')->truncate();

        foreach ($data as $val){
            $user = new User();
            $user->name = $val["name"];
            $user->email = $val["email"];
            $user->password = Hash::make($val["password"]);
            $user->save();
            $admin = new AdminUsers();
            $admin->user_id = $user->id;
            $admin->save();
        }
    }
}
