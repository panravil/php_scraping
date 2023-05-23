<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use voku\helper\HtmlDomParser;

function scrapeShopPage($paginationNumber) {
    $newsDataList = array();

    $curl = curl_init();

    $pageLink = $paginationNumber == 0 ? "https://news.ycombinator.com" : "https://news.ycombinator.com/?p=$paginationNumber";
    curl_setopt($curl, CURLOPT_URL, $pageLink);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $pageHtml = curl_exec($curl);
    curl_close($curl);

    $paginationHtmlDomParser = HtmlDomParser::str_get_html($pageHtml);

    // retrieving the list of newses on the page
    $newsElements = $paginationHtmlDomParser->find("tr.athing");

    foreach ($newsElements as $newsElement) {
        $newsDataList[] = scrapeNews($newsElement);
    }

    return $newsDataList;
}

function scrapeNews($newsElement) {
    // extracting the news data
    $title = $newsElement->findOne("td.title span a")->text;
    $created = $newsElement->next_sibling()->findOne("span.age")->getAttribute("title");
    $externalLink = $newsElement->findOne("span.titleline a")->getAttribute("href");
    $internalLink = "";
    foreach ($newsElement->next_sibling()->find('a') as $sublineAnchor) {
        $sublineAnchorHref = $sublineAnchor->getAttribute("href");
        if (strpos($sublineAnchorHref, "item?id=") !== false) {
            $internalLink = $sublineAnchorHref;
        }
    }

    // transforming the news data in an associative array
    return array(
        "title" => $title,
        "created" => $created,
        "internalLink" => $internalLink,
        "externalLink" => $externalLink,
    );
}