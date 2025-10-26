<?php

namespace Database\Seeders;

use App\Models\PunchUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adiminRole = Role::create(['name' => 'admin']);
        $adminPermission = Permission::create(['name' => 'payroll']);
        $adminPermission->assignRole($adiminRole);

        $users = [
            [
                'name' => 'Stephen Alonzo',
                'email' => 'stephen@email.com',
                'employeeId' => 2936,
                'refNo' => 1045015,
                'password' => bcrypt('password')
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@email.com',
                'employeeId' => 6246,
                'refNo' => 6656177,
                'password' => bcrypt('password')
            ],
            [
                'name' => 'Joe Smith',
                'email' => 'joe@email.com',
                'employeeId' => 9156,
                'refNo' => null,
                'password' => bcrypt('password')
            ],
        ];

        foreach ($users as $user) {
            $account = User::create($user);
            if ($user['name'] == 'Stephen Alonzo') {
                $account->assignRole('admin');
            }
        }

        $punches = [
            [
                'punch_id' => 1,
                'user_id' => 1
            ],
            [
                'punch_id' => 2,
                'user_id' => 1
            ],
            [
                'punch_id' => 3,
                'user_id' => 1
            ],
            [
                'punch_id' => 4,
                'user_id' => 1
            ],
            [
                'punch_id' => 5,
                'user_id' => 2
            ],
            [
                'punch_id' => 6,
                'user_id' => 1
            ],
            [
                'punch_id' => 7,
                'user_id' => 1
            ],
            [
                'punch_id' => 8,
                'user_id' => 2
            ],
        ];

        foreach ($punches as $punch) {
            PunchUser::create($punch);
        }
    }
}
