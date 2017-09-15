<?php

scrapeTrending("https://www.bing.com/news");

function scrapeTrending($url) {
    // echo "Scraping: " . $url . "<br>";
    $cr = curl_init($url);
    curl_setopt($cr, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, false);
    $user_agent='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36';
    curl_setopt($cr, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($cr, CURLOPT_COOKIEFILE, 'cookie.txt'); 
    $gsa = curl_exec($cr);
    curl_close($cr);
    
    // echo $gsa;
    $dom = new DOMDocument();
    @$dom->loadHTML($gsa);
    
    $classname="trd_card";
    $finder = new DomXPath($dom);

    if ($spaner = $finder->query("//*[contains(@class, '$classname')]")) {
        $i = 0;
        $n = 0;
        $matchArray = array();
    
        echo "<ul class='list-group'>";
        foreach ($spaner as $element) {
            // echo $element->textContent;
            if ($i < 10) {
                $topic = $element->getAttribute("topic-query");
                echo "<button class='list-group-item list-group-item-action btnTrending' value='" . $topic . "'>" . $topic . "</button><br>";
                // echo "<a href='searchAndSummarize.php?q=" . htmlentities($topic) . "' class='linkTrending'>" . $topic . "</a><br>";
            }
            $i++;
        }
        
        echo "</ul>";
    } else {
        echo "Topics Not Loaded";
    }
}