<?php error_reporting( E_ALL ); ?>

<html>
<head>
    <title>Search Current Events</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://bootswatch.com/spacelab/bootstrap.min.css">

    <style>
        body {
            background-color: #ffffff;
        }
        .articleText {
            font-size: 16;
        }

        #displayResultsDiv {
            height: 500px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
 


<br />
<br />  
<div class="container">
    <div class="row">
        <div class="col-lg-5">
            <h3>Summarize articles from top news sites</h3><br>
            <form id="searchBingForm">
                <div class="form-group">
                    <select id="selectSource" class="form-control input-lg" name="selectSource">
                        <option value="reuters" selected>Reuters</option>
                        <option value="associated-press">Associated Press</option>
                        <option value="bbc-news">BBC News</option>
                        <option value="google-news">Google News</option>
                        <option value="al-jazeera-english">Al Jazeera English</option>
                        <option value="ars-technica">Ars Technica</option>
                        <option value="bbc-sport">BBC Sport</option>
                        <option value="bild">Bild</option>
                        <option value="bloomberg">Bloomberg</option>
                        <option value="breitbart-news">Breitbart News</option>
                        <option value="business-insider">Business Insider</option>
                        <option value="business-insider-uk">Business Insider UK</option>
                        <option value="buzzfeed">Buzzfeed</option>
                        <option value="cnbc">CNBC</option>
                        <option value="daily-mail">Daily Mail</option>
                        <option value="engadget">Engadget</option>
                        <option value="entertainment-weekly">Entertainment Weekly</option>
                        <option value="espn">ESPN</option>
                        <option value="espn-cric-info">ESPN Cric Info</option>
                        <option value="focus">Focus</option>
                        <option value="fortune">Fortune</option>
                        <option value="hacker-news">Hacker News</option>
                        <option value="ign">IGN</option>
                        <option value="independent">Independent</option>
                        <option value="mashable">Mashable</option>
                        <option value="mtv-news">MTV News</option>
                        <option value="national-geographic">National Geographic</option>
                        <option value="new-scientist">New Scientist</option>
                        <option value="newsweek">Newsweek</option>
                        <option value="new-york-magazine">New York Magazine</option>
                        <option value="polygon">Polygon</option>
                        <option value="reddit-r-all">Reddit /r/all</option>
                        <option value="techcrunch">TechCrunch</option>
                        <option value="TechRadar">TechRadar</option>
                        <option value="the-economist">The Economist</option>
                        <option value="the-guardian-uk">The Guardian (UK)</option>
                        <option value="the-huffington-post">The Huffington Post</option>
                        <option value="the-new-york-times">The New York Times</option>
                        <option value="the-next-web">The Next Web</option>
                        <option value="the-telegraph">The Telegraph</option>
                        <option value="the-verge">The Verge</option>
                        <option value="the-wall-street-journal">The Wall Street Journal</option>
                        <option value="usa-today">USA Today</option>
                    </select>
                    <br>
                    <input type="button" class="btn btn-primary" id="btnSearchCurrentEvents" value="Search Current Events">
                </div>
            </form> 
        </div>
        <div class="col-lg-2">&nbsp;</div>
        <div class="col-lg-5">
            <h3>Summarize articles from Bing News</h3><br>
            <form id="searchBingForm">
                <div class="form-group">
                    <input type="text" class="form-control input-lg" id="txtSearchBing" placeholder="Query Bing News Articles"><br>
                    <input type="button" class="btn btn-primary" id="btnSubmitBingSearch" value="Search Bing News">
                </div>
            </form> 
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">&nbsp;</div>
        <div class="col-lg-4" id="divLoadingGif">
            <img src="includes/images/loading.gif"><br>
            <span id="bePatient">Please be patient your results are loading...</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-2">&nbsp;</div>
        <div class="col-lg-8" id='displayResultsDiv'>
            
        </div>
    </div>
</div>

 <script>
$(document).ready(function() {
    $("#divLoadingGif").hide();
    // $("#tblDisplayResults").DataTable();

    $('#txtSearchBing').keypress(function (e) {
        if (e.which == 13 || e.which == 10) {
            e.preventDefault();
            $("#displayResultsDiv").html("");
            $("#divLoadingGif").show();
            var searchWord = $("#txtSearchBing").val();
            $.post("searchAndSummarize.php", 
            {searchWord: searchWord}, 
            function(data) {
                // $.post("searchWord.php",
                // {searchWord: searchWord},
                // function(data) {
                //     $("#divLoadingGif").hide();
                //     $("#displayResultsDiv").html(data);
                // })
            })
            .done(function() {
                $.post("searchAndSummarize.php", 
                {searchWord: searchWord},
                function(data) {
                    $("#divLoadingGif").hide();
                    $("#displayResultsDiv").html(data);
                })
                $("#displayResultsDiv").show();
            })
            .fail(function() {
                $("#divLoadingGif").hide();
                alert("Search Failed.  Please Try Again Later.");
            });
            // .always(function() {
            //     getResultsWhenFail(searchWord);
            // });
        }
    });

    $('#btnSubmitBingSearch').click(function (e) {
            e.preventDefault();
            $("#displayResultsDiv").html("");
            $("#displayResultsDiv").hide();
            $("#divLoadingGif").show();
            var searchWord = $("#txtSearchBing").val();
            $.post("searchAndSummarize.php", 
            {searchWord: searchWord}, 
            function(data) {
                // $.post("searchWord.php",
                // {searchWord: searchWord},
                // function(data) {
                //     $("#divLoadingGif").hide();
                //     $("#displayResultsDiv").html(data);
                // })
            })
            .done(function() {
                $.post("searchAndSummarize.php", 
                {searchWord: searchWord},
                function(data) {
                    $("#divLoadingGif").hide();
                    $("#displayResultsDiv").html(data);
                })
                $("#displayResultsDiv").show();
            })
            .fail(function() {
                $("#divLoadingGif").hide();
                alert("Search Failed.  Please Try Again Later.");
            });
            // .always(function() {
            //     getResultsWhenFail(searchWord);
            // });
    });

    $('#btnSearchCurrentEvents').click(function (e) {
            e.preventDefault();
            $("#displayResultsDiv").html("");
            $("#displayResultsDiv").hide();
            $("#divLoadingGif").show();
            
            $.post("getCurrentEvents.php", 
            {}, 
            function(data) {
                // $.post("searchWord.php",
                // {searchWord: searchWord},
                // function(data) {
                //     $("#divLoadingGif").hide();
                //     $("#displayResultsDiv").html(data);
                // })
            })
            .done(function(data) {
                
                $("#divLoadingGif").hide();
                $("#displayResultsDiv").html(data);
                $("#displayResultsDiv").show();
            })
            .fail(function() {
                $("#divLoadingGif").hide();
                alert("Search Failed.  Please Try Again Later.");
            });
            // .always(function() {
            //     getResultsWhenFail(searchWord);
            // });
    });
    
});

</script>

</body>
</html>