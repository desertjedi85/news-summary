<?php
namespace PhpScience\TextRank;

ini_set('max_execution_time', 200);

require("vendor\autoload.php");
use Goose\Client as GooseClient;
use PhpScience\TextRank\Tool\StopWords\English;


$sources = array("google-news","reuters","associated-press");
// $source = "reuters";
$sortBy = "top";

foreach ($sources as $source) {
    $url = "https://newsapi.org/v1/articles?source=" . $source . "&sortBy=" . $sortBy . "&apiKey=6229b7f3b9034dffb6977ff90b484d5c";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $contents = curl_exec($ch);
    $myContents = json_decode($contents, true);
    
    $articles = ($myContents["articles"]);
    
    $articleArray = array();
    $titleArray = array();
    $urlArray = array();
    $errorArrayNoText = array();
    foreach ($articles as $articleData) {
        $articleUrl = $articleData["url"];
        
        if (!preg_match("/www\.cnn\.com/",$articleUrl)) {
            $urlArray[] = $articleUrl;
            $articleTitle = $articleData["title"];
            // echo $articleTitle . "<br>";
            $titleArray[] = $articleTitle;
            
            getArticle($articleUrl,$articleArray,$errorArrayNoText);
        }
    }
    
    if (count($titleArray) > 0 && count($articleArray) > 0 && count($urlArray) > 0) {
        
        for ($i = 0; $i < count($titleArray); $i++) {
            if (isset($titleArray[$i]) && isset($articleArray[$i]) && isset($urlArray[$i])) {
                echo "<div class='panel panel-default'>";
                echo "<div class='panel-heading'>";
                echo "<h4><a href=" . $urlArray[$i] . ">" . $titleArray[$i] . "</a></h4>";
                echo "</div><div class='panel-body'>";
                echo "<p class='articleText'>" . $articleArray[$i] . "</p>";
                echo "</div>";
                echo "</div>";
            }
        }
        
    } else {
        echo "Article did not return results<br>";
    }
}






function getArticle($url,&$articleArray,&$errorArrayNoText) {
    $goose = new GooseClient();
    $article = $goose->extractContent($url);
    
    if ($articleText = $article->getCleanedArticleText()) {

        // echo "ArticleText: " . $articleText . "<br><br>";

        $result = array();
        summarize($articleText,$result);

        $string = "";
        foreach ($result as $line) {
            // $string .= $line . " ";
            foreach ($line as $sentence) {
                $string .= $sentence . " ";
            }
            // var_dump($line);
        }
        $articleArray[] = $string;
        // return $articleArray;
    } else {
        // $errorArrayNoText[] = $url;
        // echo "Article not parsed<br>";
    }
    
}

function summarize($articleText,&$resultsArray) {
    // String contains a long text, see the /res/sample1.txt file.
    
    
    $api = new TextRankFacade();
    // English implementation for stopwords/junk words:
    $stopWords = new English();
    $api->setStopWords($stopWords);
    
    // Array of the most important keywords:
    // $result = $api->getOnlyKeyWords($text); 
    
    // Array of the sentences from the most important part of the text:
    $resultsArray[] = $api->getHighlights($articleText); 
    
    // Array of the most important sentences from the text:
    // $result = $api->summarizeTextBasic($text);
    // return $result;
    // print_r($result);
}