<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WSServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profile = [
            'profile' => [
                'xss_patterns' => [
                    '(?:https?://|//)[^\\s/]+\\.js',
                    '((%3C)|<)((%2F)|/)*[a-z0-9%]+((%3E)|>)',
                    '((\\%3C)|<)((\\%69)|i|(\\%49))((\\%6D)|m|(\\%4D))((\\%67)|g|(\\%47))[^\n]+((\\%3E)|>)',
                ],
                'sql_patterns' => [
                    '(?:select\\s+.+\\s+from\\s+.+)',
                    '(?:insert\\s+.+\\s+into\\s+.+)',
                ],
                'http_verb_patterns' => '(?i)(HEAD|OPTIONS|TRACE|CONNECT|PROPFIND|PROPPATCH|MKCOL|COPY|MOVE|LOCK|UNLOCK)',
                'max_size_request' => '2048',
                'patching_patterns' => [
                    '^(?!.*(\\.\\.|/|\\\\)).*$',
                ],
            ],
        ];
        DB::table('ws_services')->insert([
            [
                'name' => 'ws-common-attack-detection',
                'description' => 'Whale Sentinel Common Attack Detection Service',
                'profile' => json_encode($profile, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
