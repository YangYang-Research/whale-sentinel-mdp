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
                    'xss_pattern_1' => '(?:https?://|//)[^\s/]+\.js',
                    'xss_pattern_2' => '((%3C)|<)((%2F)|/)*[a-z0-9%]+((%3E)|>)',
                    'xss_pattern_3' => '((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)',
                    'xss_pattern_4' => '((%3C)|<)[^\n]+((%3E)|>)',
                    'xss_pattern_5' => '(?i)<script[^>]*>.*?</script>',
                    'xss_pattern_6' => '(?i)on\w+\s*=\s*["\']?[^"\'>]+["\']?',
                    'xss_pattern_7' => '(?i)javascript\s*:\s*[^"\'>\s]+',
                    'xss_pattern_8' => '(?i)eval\s*\(',
                    'xss_pattern_9' => '(?i)document\.cookie',
                    'xss_pattern_10' => '(?i)alert\s*\(',
                    'xss_pattern_11' => '(?i)prompt\s*\(',
                    'xss_pattern_12' => '(?i)confirm\s*\(',
                    'xss_pattern_13' => '(?i)onload\s*=\s*[^"\'>]+',
                    'xss_pattern_14' => '(?i)onerror\s*=\s*[^"\'>]+',
                    'xss_pattern_15' => '(?i)onmouseover\s*=\s*[^"\'>]+',
                    'xss_pattern_16' => '(?i)onfocus\s*=\s*[^"\'>]+',
                    'xss_pattern_17' => '(?i)onblur\s*=\s*[^"\'>]+',
                    'xss_pattern_18' => '(?i)onchange\s*=\s*[^"\'>]+',
                    'xss_pattern_19' => '(?i)onsubmit\s*=\s*[^"\'>]+',
                    'xss_pattern_20' => '(?i)onreset\s*=\s*[^"\'>]+',
                    'xss_pattern_21' => '(?i)onselect\s*=\s*[^"\'>]+',
                    'xss_pattern_22' => '(?i)onkeydown\s*=\s*[^"\'>]+',
                    'xss_pattern_23' => '(?i)onkeypress\s*=\s*[^"\'>]+',
                    'xss_pattern_24' => '(?i)onmousedown\s*=\s*[^"\'>]+',
                    'xss_pattern_25' => '(?i)onmouseup\s*=\s*[^"\'>]+',
                    'xss_pattern_26' => '(?i)onmousemove\s*=\s*[^"\'>]+',
                    'xss_pattern_27' => '(?i)onmouseout\s*=\s*[^"\'>]+',
                    'xss_pattern_28' => '(?i)onmouseenter\s*=\s*[^"\'>]+',
                    'xss_pattern_29' => '(?i)onmouseleave\s*=\s*[^"\'>]+',
                    'xss_pattern_30' => '(?i)oncontextmenu\s*=\s*[^"\'>]+',
                    'xss_pattern_31' => '(?i)onresize\s*=\s*[^"\'>]+',
                    'xss_pattern_32' => '(?i)onscroll\s*=\s*[^"\'>]+',
                    'xss_pattern_33' => '(?i)onwheel\s*=\s*[^"\'>]+',
                    'xss_pattern_34' => '(?i)oncopy\s*=\s*[^"\'>]+',
                    'xss_pattern_35' => '(?i)oncut\s*=\s*[^"\'>]+',
                    'xss_pattern_36' => '(?i)onpaste\s*=\s*[^"\'>]+',
                    'xss_pattern_37' => '(?i)onbeforeunload\s*=\s*[^"\'>]+',
                    'xss_pattern_38' => '(?i)onhashchange\s*=\s*[^"\'>]+',
                    'xss_pattern_39' => '(?i)onmessage\s*=\s*[^"\'>]+',
                    'xss_pattern_40' => '(?i)onoffline\s*=\s*[^"\'>]+',
                    'xss_pattern_41' => '(?i)ononline\s*=\s*[^"\'>]+',
                    'xss_pattern_42' => '(?i)onpagehide\s*=\s*[^"\'>]+',
                    'xss_pattern_43' => '(?i)onpageshow\s*=\s*[^"\'>]+',
                    'xss_pattern_44' => '(?i)onpopstate\s*=\s*[^"\'>]+',
                    'xss_pattern_45' => '(?i)onstorage\s*=\s*[^"\'>]+',
                    'xss_pattern_46' => '(?i)onunload\s*=\s*[^"\'>]+',
                    'xss_pattern_47' => '(?i)oninput\s*=\s*[^"\'>]+',
                    'xss_pattern_48' => '(?i)oninvalid\s*=\s*[^"\'>]+',
                    'xss_pattern_49' => '(?i)onsearch\s*=\s*[^"\'>]+',
                    'xss_pattern_50' => '(?i)onkeyup\s*=\s*[^"\'>]+',
                    'xss_pattern_51' => '(?i)onabort\s*=\s*[^"\'>]+',
                    'xss_pattern_52' => '(?i)oncanplay\s*=\s*[^"\'>]+',
                    'xss_pattern_53' => '(?i)oncanplaythrough\s*=\s*[^"\'>]+',
                    'xss_pattern_54' => '(?i)ondurationchange\s*=\s*[^"\'>]+',
                    'xss_pattern_55' => '(?i)onemptied\s*=\s*[^"\'>]+',
                    'xss_pattern_56' => '(?i)onended\s*=\s*[^"\'>]+',
                    'xss_pattern_57' => '(?i)onloadeddata\s*=\s*[^"\'>]+',
                    'xss_pattern_58' => '(?i)onloadedmetadata\s*=\s*[^"\'>]+',
                    'xss_pattern_59' => '(?i)onloadstart\s*=\s*[^"\'>]+',
                    'xss_pattern_60' => '(?i)onpause\s*=\s*[^"\'>]+',
                    'xss_pattern_61' => '(?i)onplay\s*=\s*[^"\'>]+',
                    'xss_pattern_62' => '(?i)onplaying\s*=\s*[^"\'>]+',
                    'xss_pattern_63' => '(?i)onprogress\s*=\s*[^"\'>]+',
                    'xss_pattern_64' => '(?i)onratechange\s*=\s*[^"\'>]+',
                    'xss_pattern_65' => '(?i)onseeked\s*=\s*[^"\'>]+',
                    'xss_pattern_66' => '(?i)onseeking\s*=\s*[^"\'>]+',
                    'xss_pattern_67' => '(?i)onstalled\s*=\s*[^"\'>]+',
                    'xss_pattern_68' => '(?i)onsuspend\s*=\s*[^"\'>]+',
                    'xss_pattern_69' => '(?i)ontimeupdate\s*=\s*[^"\'>]+',
                    'xss_pattern_70' => '(?i)onvolumechange\s*=\s*[^"\'>]+',
                    'xss_pattern_71' => '(?i)onwaiting\s*=\s*[^"\'>]+',
                    'xss_pattern_72' => '(?i)onshow\s*=\s*[^"\'>]+',
                    'xss_pattern_73' => '(?i)onvisibilitychange\s*=\s*[^"\'>]+',
                    'xss_pattern_74' => '(?i)onanimationstart\s*=\s*[^"\'>]+',
                    'xss_pattern_75' => '(?i)onanimationend\s*=\s*[^"\'>]+',
                    'xss_pattern_76' => '(?i)onanimationiteration\s*=\s*[^"\'>]+',
                    'xss_pattern_77' => '(?i)ontransitionend\s*=\s*[^"\'>]+',
                ],

                'sql_patterns' => [
                    'sqli_pattern_1' => '(?:select\s+.+\s+from\s+.+)',
                    'sqli_pattern_2' => '(?:insert\s+.+\s+into\s+.+)',
                    'sqli_pattern_3' => '(?:update\s+.+\s+set\s+.+)',
                    'sqli_pattern_4' => '(?:delete\s+.+\s+from\s+.+)',
                    'sqli_pattern_5' => '(?:drop\s+.+)',
                    'sqli_pattern_6' => '(?:truncate\s+.+)',
                    'sqli_pattern_7' => '(?:alter\s+.+)',
                    'sqli_pattern_8' => '(?:exec\s+.+)',
                    'sqli_pattern_9' => '(\s*(all|any|not|and|between|in|like|or|some|contains|containsall|containskey)\s+.+[\=\>\<=\!\~]+.+)',
                    'sqli_pattern_10' => '(?:let\s+.+[\=]\s+.*)',
                    'sqli_pattern_11' => '(?:begin\s*.+\s*end)',  
                    'sqli_pattern_12' => '(?:\s*[\/\*]+\s*.+\s*[\*\/]+)',                      
                    'sqli_pattern_13' => '(?:\s*(\-\-)\s*.+\s+)',                            
                    'sqli_pattern_14' => '(?:\s*(contains|containsall|containskey)\s+.+)',   
                    'sqli_pattern_15' => '\w*((%27)|(\'))((%6F)|o|(%4F))((%72)|r|(%52))',   
                    'sqli_pattern_16' => 'exec(\s|\+)+(s|x)p\w+',                            
                    'sqli_pattern_17' => '(?i)\b(select|insert|update|delete|drop|exec|union)\b', 
                    'sqli_pattern_18' => '(?i)(\bor\b|\band\b).*(=|>|<|!=)',
                    'sqli_pattern_19' => '(?i)\'\s*(or|and)\s*\'\s*=\s*\'',
                    'sqli_pattern_20' => '(?i)\'\s*(or|and)\s*\'[^=]*=\'',
                ],

                'http_verb_patterns' => '(?i)(GET|POST|PUT|DELETE)',
                'max_size_request' => '2',
                'unknow_attack_patterns' => [
                   'unknow_pattern_1' => '(?:\.\./|\.\.\\)+',
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
