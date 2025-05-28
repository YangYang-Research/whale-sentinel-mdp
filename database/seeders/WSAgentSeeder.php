<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WSAgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instanceId = DB::table('ws_instances')->value('id');

        $profile = [
            'profile' => [
                'running_mode' => 'monitor',
                'last_run_mode' => 'lite',
                'lite_mode_data_is_synchronized' => false,
                'lite_mode_data_synchronize_status' => 'fail',
                'ws_module_web_attack_detection' => [
                    'enable' => true,
                    'detect_header' => false,
                    'threshold' => 80,
                ],
                'ws_module_dga_detection' => [
                    'enable' => true,
                    'threshold' => 80,
                ],
                'ws_module_common_attack_detection' => [
                    'enable' => true,
                    'detect_cross_site_scripting' => true,
                    'detect_http_large_request' => true,
                    'detect_sql_injection' => true,
                    'detect_http_verb_tampering' => true,
                ],
                'secure_response_headers' => [
                    'enable' => true,
                    'headers' => [
                        'Server' => 'Whale Sentinel',
                        'X-Whale-Sentinel' => '1',
                        'Referrer-Policy' => 'no-referrer-when-downgrade',
                        'X-Content-Type-Options' => 'nosniff',
                        'X-XSS-Protection' => '1; mode=block',
                        'X-Frame-Options' => 'SAMEORIGIN',
                        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
                        'X-Permitted-Cross-Domain-Policies' => 'none',
                        'Expect-CT' => 'enforce; max-age=31536000',
                        'Feature-Policy' => "fullscreen 'self'",
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        'X-UA-Compatible' => 'IE=Edge,chrome=1',
                        'Access-Control-Allow-Origin' => '*',
                        'Access-Control-Allow-Methods' => 'GET, POST',
                        'Access-Control-Allow-Credentials' => 'true',
                        'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                    ],
                ],
            ],
        ];
        DB::table('ws_agents')->insert([
            [
                'name' => 'ws_agent_1',
                'description' => 'Whale Sentinel Agent 1',
                'profile' => json_encode($profile, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
                'instance_id' => $instanceId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
