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
//$guzzleHttpClient = new GuzzleHttp\Client();
//$crawler = new Crawler();

echo COLOR_GREEN . "\n-------------------- Begin --------------------" . COLOR_WHITE;

$client = \Symfony\Component\Panther\Client::createChromeClient();
$resultSet = [];
$url = 'http://atviri.siauliai.lt/demografiniai-duomenys/';
echo "\nCrawling $url" . PHP_EOL;
$crawler = $client->request('GET', $url);

//print_r(extractMetaInfo($crawler));
print_r(getChildLinks($crawler));


/*
//$client->reload()

$resultSet[$url] = getUrlSeoInfo($client, $url);

$i=0;
$countLinks = count($resultSet[$url]['childrenLinks']);
foreach($resultSet[$url]['childrenLinks'] as $childUrl){
    $isExternal = false;
    $childAbsoluteUrl = getAbsoluteUrl((string)$childUrl, $url, $isExternal);

    $i++;
    echo "[{$i}/{$countLinks}] Crawling $childAbsoluteUrl".PHP_EOL;
    if($isExternal === false){
        $resultSet[$childUrl] = getUrlSeoInfo($client, $childAbsoluteUrl);
    }
}
*/

/*
$response = $guzzleHttpClient->get('http://atviri.siauliai.lt/demografiniai-duomenys/');



echo COLOR_GREEN . "\nStatus code: " . $response->getStatusCode() . COLOR_WHITE . "\n";

$html = $response->getBody();
$crawler->addHtmlContent($html);
$links = $crawler->filter('a')->links();
var_dump($links);
*/
/*
$client = \Symfony\Component\Panther\Client::createChromeClient();

$crawler = $client->request('GET', 'http://atviri.siauliai.lt/demografiniai-duomenys/');
*/
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
/*
$crawler->filter('body')->children()->each(function (Crawler $c, int $i){
    //echo "\n{$c->text()->nodeName()}";
    echo "\n{$c->text()}";
});
*/
///** @var Link[] $links */
//$links = $crawler->filter('a')->links();
//foreach ($links as $link) {
//    print_r($link);
//}

echo "\n";
echo COLOR_GREEN . "\n-------------------- End ----------------------\n" . COLOR_WHITE;

function extractMetaInfo(DomCrawler $crawler): array
{
    $linkMetaInfo = [];
    $linkMetaInfo['title'] = trim(strip_tags($crawler->filter('title')->html()));
    $crawler->filterXPath('//meta[@name="description"]')->each(function (DomCrawler $node) use (&$linkMetaInfo) {
        $linkMetaInfo['metaDescription'] = strip_tags($node->attr('content'));
    });
    $crawler->filterXPath('//meta[@name="keywords"]')->each(function (DomCrawler $node) use (&$linkMetaInfo) {
        $linkMetaInfo['metaKeywords'] = trim($node->attr('content'));
    });

    $crawler->filterXPath('//link[@rel="canonical"]')->each(function (DomCrawler $node) use (&$linkMetaInfo) {
        $linkMetaInfo['canonicalLink'] = trim($node->attr('href'));
    });

    $h1Count = $crawler->filter('h1')->count();
    if ($h1Count > 0) {
        $crawler->filter('h1')->each(function (DomCrawler $node, $i) use (&$linkMetaInfo) {
            $linkMetaInfo['h1Contents'][] = trim($node->text());
        });
    }

    $h2Count = $crawler->filter('h2')->count();
    if ($h2Count > 0) {
        $crawler->filter('h2')->each(function (DomCrawler $node, $i) use (&$linkMetaInfo) {
            $linkMetaInfo['h2Contents'][] = trim($node->text());
        });
    }

    return $linkMetaInfo;
}

function getChildLinks(DomCrawler $crawler): array
{
    $childLinks = [];
    $crawler->filter('a')->each(function (DomCrawler $node, $i) use (&$childLinks) {
        $hrefVal = $node->extract('href')[0];
        $childLinks[] = is_array($hrefVal) ? current($hrefVal) : $hrefVal;
    });

    return $childLinks;
}

function getAbsoluteUrl($childUrl, $fromUrl, &$isExternal): string
{

    $childPageUri = new GuzzleHttp\Psr7\Uri($childUrl);
    $fromPageUri = new GuzzleHttp\Psr7\Uri($fromUrl);

    if ($childPageUri->getHost() !== $fromPageUri->getHost() && $childPageUri !== "") {
        $isExternal = true;
    } else {
        $isExternal = false;
    }

    $newUri = \GuzzleHttp\Psr7\UriResolver::resolve($fromPageUri, $childPageUri);
    $absolutePath = \GuzzleHttp\Psr7\Uri::composeComponents(
        $newUri->getScheme(),
        $newUri->getAuthority(),
        $newUri->getPath(),
        $newUri->getQuery(),
        ""
    );
    return $absolutePath;
}

function getUrlHeaders($url): array
{
    // overriding the default stream context to disable ssl checking
    stream_context_set_default([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ]);

    $headersArrRaw = get_headers($url, 1);
    if ($headersArrRaw === false) {
        throw new \Exception("cannot get headers for {$url}");
    }

    $headersArr = array_change_key_case($headersArrRaw, CASE_LOWER);
    if (isset($headersArr[0]) === true && strpos($headersArr[0], 'HTTP/') !== false) {
        $statusStmt = $headersArr[0];
        $statusParts = explode(' ', $statusStmt);
        $headersArr['status-code'] = $statusParts[1];

        $statusIndex = strrpos($statusStmt, $statusParts[1]) + strlen($statusParts[1]) + 1;
        $headersArr['status'] = trim(substr($statusStmt, $statusIndex));
    }
    if (is_array($headersArr['content-type']) === true) {
        $headersArr['content-type'] = end($headersArr['content-type']);
    }

    return $headersArr;
}
