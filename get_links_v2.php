<?php
require __DIR__ . '/vendor/autoload.php'; // Composer's autoloader

use Facebook\WebDriver\WebDriverElement;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Image;
use Symfony\Component\Panther\DomCrawler\Link;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\Panther\Client as PantherClient;

const COLOR_WHITE = "\033[0m";
const COLOR_RED = "\033[31m";
const COLOR_GREEN = "\033[32m";
$guzzleHttpClient = new GuzzleHttp\Client();

echo COLOR_GREEN . "\n-------------------- Begin --------------------" . COLOR_WHITE;
$response = $guzzleHttpClient->get('http://atviri.siauliai.lt/demografiniai-duomenys/');

echo COLOR_GREEN . "\nStatus code: " . $response->getStatusCode() . COLOR_WHITE . "\n";
$html = $response->getBody();

$dom = new DOMDocument;

@$dom->loadHTML($html);
$styles = $dom->getElementsByTagName('link');
$links = $dom->getElementsByTagName('a');
$scripts = $dom->getElementsByTagName('script');

foreach ($styles as $style) {

    if ($style->getAttribute('href') != "#") {
        echo $style->getAttribute('href');
        echo "\n";
    }
}

foreach ($links as $link) {

    if ($link->getAttribute('href') != "#") {
        echo $link->getAttribute('href');
        echo "\n";
    }
}

foreach ($scripts as $script) {

    echo $script->getAttribute('src');
    echo "\n";

}


echo "\n";
echo COLOR_GREEN . "\n-------------------- End ----------------------\n" . COLOR_WHITE;
