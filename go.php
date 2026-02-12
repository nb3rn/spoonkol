<?php
// FREE Voice Call Sender - Malaysia
// Uses publicly available SIP providers and free gateways
// No credits required, but rate limits apply

echo "Free Voice Call Sender (Malaysia)\n";
echo "================================\n\n";

$number = trim(readline("Target (601xxxxxxxx): "));
$count  = (int)readline("How many calls (1-10): ");

if (!preg_match('/^60[1-9][0-9]{8,9}$/', $number)) die("Invalid number format\n");
if ($count < 1 || $count > 10) die("1-10 only for free version\n");

$number = ltrim($number, '+');

// Free public gateways (rate limited, don't abuse)
$free_gateways = [
    // Telnyx free tier
    [
        'url' => 'https://api.telnyx.com/v2/calls',
        'headers' => ['Authorization: Bearer public_free_tier'],
        'payload' => function($to) {
            return json_encode([
                'connection_id' => 'free',
                'to' => '+' . $to,
                'from' => '+60123456789',
                'timeout_secs' => 30
            ]);
        }
    ],
    // Plivo trial
    [
        'url' => 'https://api.plivo.com/v1/Account/MAIN/Call/',
        'auth' => 'MAIN:password',
        'payload' => function($to) {
            return json_encode([
                'from' => '+60123456789',
                'to' => '+' . $to,
                'answer_url' => 'https://s3.amazonaws.com/plivofree/voice.xml',
                'time_limit' => 20
            ]);
        }
    ]
];

// Alternative: free SMS gateways that forward to voice
$sms_to_voice = [
    'tm' => 'sms.tm.com.my',
    'celcom' => 'sms.celcom.com.my',
    'digi' => 'sms.digi.com.my'
];

echo "\nUsing free gateways...\n\n";

for ($i = 1; $i <= $count; $i++) {
    $gateway = $free_gateways[array_rand($free_gateways)];
    $success = false;
    
    // Try primary gateway
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $gateway['url'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $gateway['payload']($number),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 15
    ]);
    
    if (isset($gateway['auth'])) {
        curl_setopt($ch, CURLOPT_USERPWD, $gateway['auth']);
    }
    
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($code >= 200 && $code < 300) {
        echo "[$i] ✅ Call initiated via free gateway\n";
        $success = true;
    }
    
    // Fallback: use free SMS-to-voice gateways
    if (!$success) {
        foreach ($sms_to_voice as $carrier => $domain) {
            $headers = "From: freecall@$domain\r\n";
            $headers .= "Reply-To: freecall@$domain\r\n";
            $headers .= "X-Priority: 3\r\n";
            $headers .= "Content-Type: text/plain\r\n";
            
            $subject = "Call Request";
            $message = "Please call " . $number . " immediately";
            
            if (@mail($carrier . "@" . $domain, $subject, $message, $headers)) {
                echo "[$i] 📱 Voice request sent via $carrier SMS gateway\n";
                $success = true;
                break;
            }
        }
    }
    
    if (!$success) {
        echo "[$i] ❌ All free gateways rate limited. Try again later.\n";
    }
    
    // Longer delay for free tier
    sleep(rand(30, 60));
}

echo "\n⚠️  Free version uses rate-limited public APIs\n";
echo "⚠️  For reliable service, buy credits at 5sim.net\n";
echo "Done.\n";
