<?php
require __DIR__.'/vendor/autoload.php'; // Composer's autoloader

use Facebook\WebDriver\WebDriverElement;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Image;
use Symfony\Component\Panther\DomCrawler\Link;
use Symfony\Component\Panther\Tests\TestCase;

const COLOR_WHITE = "\033[0m";
const COLOR_RED = "\033[31m";
const COLOR_GREEN = "\033[32m";

echo COLOR_GREEN . "\n-------------------- Begin --------------------" . COLOR_WHITE;

$client = \Symfony\Component\Panther\Client::createChromeClient();

$crawler = $client->request('GET', 'http://atviri.siauliai.lt/demografiniai-duomenys/');

/*
foreach ($crawler as $element) {
    //echo "\n" . $element instanceof \DOMElement ? $element->tagName : $element->getTagName();
    echo "\n{$element->getTagName()}";
}
*/
/*
$crawler->filterXPath('descendant-or-self::body/p')->each(function (Crawler $crawler, int $i) {
    echo $crawler->text();
});
*/
/*
$crawler->filterXPath('descendant-or-self::table')->each(function (Crawler $crawler, int $i) {
    echo $crawler->text();
});
*/
$crawler->filter('body')->children()->each(function (Crawler $c, int $i){
    //echo "\n{$c->text()->nodeName()}";
    echo "\n{$c->text()}";
});

echo "\n";
echo COLOR_GREEN . "\n-------------------- End ----------------------\n" . COLOR_WHITE;

