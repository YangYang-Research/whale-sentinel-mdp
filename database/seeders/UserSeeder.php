<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'Administrator',
			'slug' => 'administrator',
			'permissions' => [
				'admin' => true,
				'dashboard.*' => true,
				'ws_instance.*' => true,
				'ws_agent.*' => true,
                'ws_service.*' => true,
                'ws_profile.*' => true,
				'user.*' => true,
				'role.*' => true,
				'trash.*' => true,
			]
		]);

        $admin = [
			'first_name' => 'Super',
			'last_name' => 'Admin',
			'email'    => 'superadmin@yangyang.research',
			'password' => 'H@ytR40ch0@',
		];
		$adminUser = Sentinel::registerAndActivate($admin);
		$adminRole->users()->attach($adminUser);
    }
}
