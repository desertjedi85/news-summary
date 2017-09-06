<?php error_reporting( E_ALL ); ?>

<html>
<head>
    <title>Search Current Events</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
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

        #small {

        }

        #big {

        }
    </style>
</head>

<body>
 


<br />
<br />  
<div class="container">
    <div class="row">
        <div id="topDiv" class="big">
            <div class="col-lg-5">
                <div class="searchHeader">
                    <h3>Summarize articles from top news sites</h3><br>
                </div>
                <form id="searchBingForm">
                    <div class="form-group">
                        <select id="selectSource" class="form-control input-lg" name="selectSource">
                            <option value="reuters" selected>Reuters</option>
                            <option value="bbc-news">BBC News</option>
                            <option value="google-news">Google News</option>
                            <option value="ars-technica">Ars Technica</option>
                            <option value="bbc-sport">BBC Sport</option>
                            <option value="bloomberg">Bloomberg</option>
                            <option value="business-insider">Business Insider</option>
                            <option value="business-insider-uk">Business Insider UK</option>
                            <option value="espn">ESPN</option>
                            <option value="ign">IGN</option>
                            <option value="independent">Independent</option>
                            <option value="newsweek">Newsweek</option>
                            <option value="new-york-magazine">New York Magazine</option>
                            <option value="polygon">Polygon</option>   
                            <option value="TechRadar">TechRadar</option>
                            <option value="the-economist">The Economist</option>
                            <option value="the-guardian-uk">The Guardian (UK)</option>
                            <option value="the-new-york-times">The New York Times</option>
                            <option value="the-verge">The Verge</option>
                        </select>
                        <br>
                        <input type="button" class="btn btn-primary" id="btnSearchCurrentEvents" value="Search Current Events">
                    </div>
                </form> 
            </div>
        </div>
        <div class="col-lg-2">&nbsp;</div>
        <div class="col-lg-5">
            <div class="searchHeader">
                <h3>Summarize articles from Bing News</h3><br>
            </div>
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

<footer class="footer" id="footer">
<span class="text-muted">Powered by NewsApi.org</span>
</footer>

 <script>
$(document).ready(function() {
    $("#displayResultsDiv").scroll(function() {
        // alert ($("#displayResultsDiv").scrollTop());
        if ($("#displayResultsDiv").scrollTop() > 2) {
            if ($("#topDiv").hasClass("big")) {
                $("#topDiv").attr("class", "small");
                $(".searchHeader").hide();
            }
        } else {
            if ($("#topDiv").hasClass("small")) {
                $("#topDiv").attr("class", "big");
                $(".searchHeader").show();
            }
        }
        
    });

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
            var source = $("#selectSource").val();
            // alert(source);
            $.post("getCurrentEvents.php", 
            {source: source}, 
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