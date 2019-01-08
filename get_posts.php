<?php
require_once "/home/user/Projects/Hitmeister/sms/vendor/autoload.php";

const COLOR_WHITE = "\033[0m";
const COLOR_RED = "\033[31m";
const COLOR_GREEN = "\033[32m";

echo COLOR_GREEN . "\n-------------------- Begin --------------------" . COLOR_WHITE;

$guzzleHttpClient = new GuzzleHttp\Client();

try {
    $response = $guzzleHttpClient->get("http://atviri.siauliai.lt/wp-json/wp/v2/posts?per_page=100&page=1",
    //$response = $guzzleHttpClient->get("http://atviri.siauliai.lt/wp-json/wp/v2/posts?per_page=100&page=1&search=lankomumas",
    //$response = $guzzleHttpClient->get("http://atviri.siauliai.lt/wp-json/wp/v2/posts?search=pop",
    //$response = $guzzleHttpClient->get("http://atviri.siauliai.lt/wp-json/wp/v2/posts/1248",
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
    //print_r($jsonObject);
    $count = 0;
    
    foreach($jsonObject as $post) {
        ++$count;
        echo "\n{$post->id} - {$post->title->rendered}:";
        foreach($post->_links->self as $link)
        {
            echo "\n\t$link->href";
        }
    }
    echo "\n---------------------";
    echo "\nTotal found: {$count}";
} catch (Exception $e) {
    echo COLOR_RED . "\nCaught exception: " .  $e->getMessage();
}
echo "\n";
echo COLOR_GREEN . "\n-------------------- End ----------------------\n" . COLOR_WHITE;

