<?php

$sortBy = "top";
$source = "reuters";

$url = "https://newsapi.org/v1/articles?source=" . $source . "&sortBy=" . $sortBy . "&apiKey=6229b7f3b9034dffb6977ff90b484d5c";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$contents = curl_exec($ch);

$info = curl_getinfo($ch);
$myContents = json_decode($contents, true);

echo "Response Code: " . $info["http_code"] . "<br>";
echo "Status: " . $myContents["status"];