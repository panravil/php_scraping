<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/constants.php';
require_once  __DIR__ . '/utils/scraping.php';

$countToFetch = 100;
$paginationNumber = 0;
$newsList = array();
// iterate over pages and retrieve news data until newsList count is more than 100
while (count($newsList) < $countToFetch) {
    $newsList = array_merge($newsList, scrapeShopPage($paginationNumber));
    $paginationNumber++;
}
// retrieve first 100 newses from the newsList
$newsList100 = array_slice($newsList, 0, 100);

// Iterate through the array and display the data
$count = 0;
foreach ($newsList100 as $item) {
    $count++;
    echo "Number: " . $count . "<br>";
    echo "Title: " . $item['title'] . "<br>";
    echo "Created: " . $item['created'] . "<br>";
    echo "Internal Link: " . $item['internalLink'] . "<br>";
    echo "External Link: " . $item['externalLink'] . "<br><br>";
}
