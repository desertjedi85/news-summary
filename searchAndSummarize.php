<?php
namespace PhpScience\TextRank;

ini_set('max_execution_time', 200);

require("/home/searchcu/public_html/vendor/autoload.php");
use Goose\Client as GooseClient;
use PhpScience\TextRank\Tool\StopWords\English;

if (isset($_POST["searchWord"])) {
    $query = htmlspecialchars($_POST["searchWord"]);
    $query = str_replace(" ", "+", $query);
} else {
    // $query = "bitcoin";
}

// $url = "https://www.nytimes.com/2017/08/30/us/hurricane-center-timeline.html&p=devex";

$urlARRAY = array();
// $blacklistArray = array("cnn.com");
getBingResults($query,$urlARRAY);

foreach ($urlARRAY as $url) {
    $titleArray = array();
    $articleArray = array();
    $errorArrayNoText = array();
    
    if (remoteURLExists($url) === true) {
        if (!preg_match("/www\.cnn\.com/",$url) && !preg_match("/www\.usatoday\.com/",$url) && !preg_match("/www\.foxsports\.com/",$url) && !preg_match("/www\.ft\.com/",$url) && !preg_match("/www\.time\.com/",$url)) {
            getArticle($url,$titleArray,$articleArray,$errorArrayNoText);
            
            if (count($titleArray) > 0 && count($articleArray) > 0) {
                echo "<div class='panel panel-info'>";
                for ($i=0; $i < count($titleArray); $i++) {
                    echo "<div class='panel-heading'><h4><a href=".$url.">" . $titleArray[$i] . "</a></h4></div><br>";
                    echo "<div class='panel-body'><p class='articleText'>" . $articleArray[$i] . "</p></div><br><br>";  
                }
                echo "</div>";
            } else {
                "Error returning articles.<br><br>";
            }

            if (count($errorArrayNoText) > 0) {
                echo "<div class='panel panel-info'>";
                echo "<div class='panel-heading'><h4>" . count($errorArrayNoText) . " URLs had errors</h4></div>";
                echo "<div class='panel-body'>";
                foreach ($errorArrayNoText as $errorUrl) {
                    echo "<p class='articleText'>" . $errorUrl . "</p>";
                }
                echo "</div>";
            }
        }
    }
}

function getArticle($url,&$titleArray,&$articleArray,&$errorArrayNoText) {
    $goose = new GooseClient();
    $article = $goose->extractContent($url);
    
    if ($articleText = $article->getCleanedArticleText()) {
        // echo "ArticleText: " . $articleText . "<br><br>";
        $articleTitle = $article->getTitle();
        if (!isset($articleTitle) && !isset($articleText)) {
            $articleTitle = "Article Data Could Not Be Retrieved";
        }

        
        $result = array();
        summarize($articleText,$result);
        $titleArray[] = $articleTitle;

        $string = "";
        foreach ($result as $line) {
            $string .= $line . " ";
        }
        $articleArray[] = $string;
        // return $articleArray;
    } else {
        $errorArrayNoText[] = $url;
    }
    
}

function summarize($text,&$result) {
    // String contains a long text, see the /res/sample1.txt file.
    
    
    $api = new TextRankFacade();
    // English implementation for stopwords/junk words:
    $stopWords = new English();
    $api->setStopWords($stopWords);
    
    // Array of the most important keywords:
    // $result = $api->getOnlyKeyWords($text); 
    
    // Array of the sentences from the most important part of the text:
    $result = $api->getHighlights($text); 
    
    // Array of the most important sentences from the text:
    // $result = $api->summarizeTextBasic($text);
    return $result;
}

function getBingResults($query,&$urlARRAY) {
    // Bing Endpoints
    $newsURL = "https://api.cognitive.microsoft.com/bing/v5.0/news/search";

    // $query = "Bitcoin";
    $count = 30;
    $sURL = $newsURL."?q=".$query."&count=".$count."&mkt=en-US";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $sURL); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: multipart/form-data',
        'Ocp-Apim-Subscription-Key: 0aa4ba06e81545688052db6a517a3a1e'
    ));

    $contents = curl_exec($ch);
    $myContents = json_decode($contents);
    if(count($myContents->value) > 0) {
        
        foreach ($myContents->value as $content) {
            // echo $content->url . "<br>";
            $url = $content->url;

            if (preg_match('/,/',$url)) {
                $splitLine = preg_split('/,/',$url);
                foreach ($splitLine as $url) {
                    if (preg_match('/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/',$url)) {
                        if (preg_match_all('/(https:\/\/http:\/\/|www\.)bing\.com(.*)?[r]=(.*)/',$url,$match)) {
                            $url = $match[3][0];
                            $url = rawurldecode($url);
                            if (preg_match('/\w{1}/',$url)) {
                                addURL($url,$query,$urlARRAY);
                            }
                        } else {
                            $url = rawurldecode($url);
                            if (preg_match('/\w{1}/',$url)) {
                                addURL($url,$query,$urlARRAY);
                            }
                        }
                    }
                }
            } else {
                if (preg_match('/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/',$url)) {
                    if (preg_match_all('/(https:\/\/http:\/\/|www\.)bing\.com(.*)?[r]=(.*)/',$splitLine,$match)) {
                        $url = $match[3][0];
                        $url = rawurldecode($url);
                        if (preg_match('/\w{1}/',$url)) {
                            addURL($url,$query,$urlARRAY);
                        }
                    } else {
                        $url = rawurldecode($url);
                        if (preg_match('/\w{1}/',$url)) {
                            addURL($url,$query,$urlARRAY);
                        }
                    }
                }
            }
        }
    }
}


function addURL ($url,$query,&$urlARRAY) {
    $url = strtolower($url);
    $query = strtolower($query);

    $urlARRAY[] = $url;
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