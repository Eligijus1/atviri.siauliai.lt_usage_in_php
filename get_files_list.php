<?php
require_once "/home/user/Projects/Hitmeister/sms/vendor/autoload.php";

const COLOR_WHITE = "\033[0m";
const COLOR_RED = "\033[31m";
const COLOR_GREEN = "\033[32m";

echo COLOR_GREEN . "\n-------------------- Begin --------------------" . COLOR_WHITE;

$guzzleHttpClient = new GuzzleHttp\Client();

try {
    $response = $guzzleHttpClient->get("http://atviri.siauliai.lt/wp-json/wp/v2/media?per_page=100",
    [
        'headers' => [
            'Accept' => 'application/json', 
            'Content-Type' => 'application/json'
        ]
    ]);  
    
    echo COLOR_GREEN . "\nStatus code: " . $response->getStatusCode() . COLOR_WHITE . "\n";

    // Extract and prepare json object:
    $jsonObject = json_decode($response->getBody());
    
    // Print JSON:
    $count = 0;
    
    foreach($jsonObject as $mediaObject) {
        ++$count;
        echo "\n{$mediaObject->source_url}";
    }
    echo "\n---------------------";
    echo "\nTotal found: {$count}";
} catch (Exception $e) {
    echo COLOR_RED . "\nCaught exception: " .  $e->getMessage();
}
echo "\n";
echo COLOR_GREEN . "\n-------------------- End ----------------------\n" . COLOR_WHITE;

