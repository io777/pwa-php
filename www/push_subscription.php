<?php
$subscription = json_decode(file_get_contents('php://input'), true);

if (!isset($subscription['endpoint'])) {
    echo 'Error: not a subscription';
    return;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // create a new subscription entry in your database (endpoint is unique)
        $fp = fopen("POST.txt", "w");
        fwrite($fp, serialize($subscription));
        fclose($fp);
        break;
    case 'PUT':
        // update the key and token of subscription corresponding to the endpoint
        $fp = fopen("PUT.txt", "w");
        fwrite($fp, serialize($subscription));
        fclose($fp);
        break;
    case 'DELETE':
        // delete the subscription corresponding to the endpoint
        $fp = fopen("DELETE123.txt", "w");
        fwrite($fp, serialize($subscription));
        fclose($fp);
        break;
    default:
        echo "Error: method not handled";
        return;
}