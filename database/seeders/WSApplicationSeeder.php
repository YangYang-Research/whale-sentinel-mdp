<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WSApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instanceId = DB::table('ws_instances')->value('id');

        DB::table('ws_applications')->insert([
            [
                'name' => 'Web App Demo',
                'description' => 'Example web application demo for Whale Sentinel',
                'language' => 'python',
                'status' => 'active',
                'instance_id' => $instanceId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
