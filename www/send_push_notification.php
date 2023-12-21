<?php
require __DIR__ . '/vendor/autoload.php';
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// here I'll get the subscription endpoint in the POST parameters
// but in reality, you'll get this information in your database
// because you already stored it (cf. push_subscription.php)
$subscription = Subscription::create(json_decode(file_get_contents('php://input'), true));

$fp = fopen("SUB.txt", "w");
fwrite($fp, serialize($subscription));
fclose($fp);

$auth = array(
    'VAPID' => array(
        'subject' => 'https://localhost/',
        'publicKey' => file_get_contents(__DIR__ . '/keys/public_key.txt'), // don't forget that your public key also lives in app.js
        'privateKey' => file_get_contents(__DIR__ . '/keys/private_key.txt'), // in the real world, this would be in a secret file
    ),
);

$fp = fopen("AUTH.txt", "w");
fwrite($fp, serialize($auth));
fclose($fp);

$webPush = new WebPush($auth);

$report = $webPush->sendOneNotification(
    $subscription,
    '{"message":"Hello! 👋"}',
);

// handle eventual errors here, and remove the subscription from your server if it is expired
$endpoint = $report->getRequest()->getUri()->__toString();

if ($report->isSuccess()) {
    echo "[v] Message sent successfully for subscription {$endpoint}.";
    $fp = fopen("SEND.txt", "w");
    fwrite($fp, "[v] Message sent successfully for subscription {$endpoint}.");
    fclose($fp);
} else {
    echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
    $fp = fopen("SEND_ERROR.txt", "w");
    fwrite($fp, "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}");
    fclose($fp);
}