<?php

$subreddits = file_get_contents("includes/subreddits.csv");

$subredditArray = preg_split("/\n/",$subreddits);

foreach ($subredditArray as $subreddit) {
    $subreddit = preg_replace("/\"/","",$subreddit);
    
    
}