<?php
namespace PhpScience\TextRank;

ini_set('max_execution_time', 200);

require("/home/searchcu/public_html/vendor/autoload.php");
// require("vendor/autoload.php");

use Goose\Client as GooseClient;
use PhpScience\TextRank\Tool\StopWords\English;

if (isset($_POST["searchWord"])) {
    $query = htmlspecialchars($_POST["searchWord"]);
    $keywordQuery = htmlspecialchars($_POST["searchWord"]);
    $query = str_replace(" ", "+", $query);
} else {
    $query = "war";
    $query = str_replace(" ", "+", $query);
}

getNewsApiResults($query,$keywordQuery);



function getNewsApiResults($query,$keywordQuery) {
    // Bing Endpoints
    // $source = "reuters";

    $url = "http://beta.newsapi.org/v2/everything?q=" . $query . "&language=en&apiKey=6229b7f3b9034dffb6977ff90b484d5c";

    updateMetaData($keywordQuery);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //     'Content-Type: multipart/form-data',
    //     'Ocp-Apim-Subscription-Key: 0aa4ba06e81545688052db6a517a3a1e'
    // ));

    $contents = curl_exec($ch);
    $info = curl_getinfo($ch);
    
    if ($info["http_code"] != 200) {
        echo "<br><br>HTTP Code: " . $info["http_code"] . "<br>News Service Unavailable<br><br>";
        exit;
    }
    
    $myContents = json_decode($contents, true);
    
    $articles = ($myContents["articles"]);
    
    $articleArray = array();
    $titleArray = array();
    $urlArray = array();
    $errorArrayNoText = array();
    // foreach ($articles as $articleData) {
    for ($i = 0; $i < 5; $i++) {
        $articleUrl = $articles[$i]["url"];
    
        if (remoteURLExists($articleUrl) === true) {
            if (!preg_match("/www\.cnn\.com/",$articleUrl) && !preg_match("/www\.foxsports\.com/",$articleUrl) && !preg_match("/time\.com/",$articleUrl)) {
                // preg_replace("/https/","http",$articleUrl);
                $urlArray[] = $articleUrl;
                $articleTitle = $articles[$i]["title"];
                // echo $articleTitle . "<br>";
                $titleArray[] = $articleTitle;
                
                getArticle($articleUrl,$articleArray,$errorArrayNoText);
            }
        }
    }

    // print_r($articleArray);
    
    if (count($titleArray) > 0 && count($articleArray) > 0 && count($urlArray) > 0) {
        // updateMetaData($source);
        for ($i = 0; $i < count($titleArray); $i++) {
            if (isset($titleArray[$i]) && isset($articleArray[$i]) && isset($urlArray[$i])) {
                echo "<div class='panel panel-info'>";
                echo "<div class='panel-heading'>";
                echo "<h4><a href=" . $urlArray[$i] . ">";
                echo $titleArray[$i];
                echo "</a></h4>";
                echo "</div><div class='panel-body'>";
                echo "<p class='articleText'>";
                if ($articleArray[$i]) {
                    echo $articleArray[$i];
                } else {
                    echo "Error returning article text";
                }
                echo "</p>";
                echo "</div>";
                echo "</div>";
            }
        }
        
    } else {
        // echo "Article did not return results<br>";
    }
}

function getArticle($url,&$articleArray,&$errorArrayNoText) {
    $goose = new GooseClient();
    $article = $goose->extractContent($url);
    
    if ($articleText = $article->getCleanedArticleText()) { // Investigate if this is where things are being slowed down

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

function updateMetaData ($query) {
    require("dbconfig.php");
    $ip = $_SERVER["REMOTE_ADDR"];
    $mysqli = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) {
        echo "Errno: " . $mysqli->connect_errno . "\n";
        echo "Error: " . $mysqli->connect_error . "\n";
        // exit();
        //TODO: Add error table
    }
    $stmt = $mysqli->prepare("INSERT INTO metadata (ip,query) VALUES (?,?)");
    $stmt->bind_param('ss', $ip,$query);
    $stmt->execute();
    // echo "URL Added Successfully.";
}

function remoteURLExists($url) {
    $ch = curl_init($url);
    $user_agent='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36';
        // $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSLVERSION,CURL_SSLVERSION_DEFAULT);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_TIMEOUT_MS, 60000);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
    curl_setopt($ch, CURLOPT_TIMEOUT, 150);
    //don't fetch the actual page, you only want to check the connection is ok
    curl_setopt($ch, CURLOPT_NOBODY, true);

    //do request
    $result = curl_exec($ch);

    $ret = false;

    if (!curl_errno($ch)) {
        if ($result !== false) {
            //if request was ok, check response code
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  

            if ($statusCode == 200) {
                $ret = true;  
            }
        } else {
        }
    } else {
        $ret = false;
    }
    curl_close($ch);

    return $ret;
}