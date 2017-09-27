<?php error_reporting( E_ALL ); ?>

<html>
<head>
    <title>Get Summaries of the News!</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="Summarize articles from top news sites. Reuters, BBC News, Google News, Ars Technica, BBC Sport, Bloomberg, Business Insider, Business Insider UK, ESPN .." />
    <meta name="keywords" content="news, current events, search current events, summarize news, summarized news, get summaries of news, get summaries of current events" />
    <meta name="robots" content="index,follow" />
    <meta name="DC.title" content="Get Summaries of the News" />


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
            height: 750px;
            width: 65%;
            overflow-y: auto;
        }

        #small {

        }

        #big {

        }

        form {
            margin-top: 5px;
        }

        #trendingTopicsDiv {
            float: right;
        }

        #trendingTopicsHeader {
            color: #ffffff;
        }

        #divLoadingGif
        {
        display : none;
        }

        #divLoadingGifMobile {
            display: none;
        }
        #divLoadingGif.show
        {
        display : block;
        position : fixed;
        z-index: 100;
        background-image : url('includes/images/loading.gif');
        /* background-color:#fff; */
        opacity : 0.6;
        background-repeat : no-repeat;
        background-position : inherit;
        left : 25%;
        bottom : 0;
        right : 0;
        top : 50%;
        margin: 0 auto;
        }
        #divLoadingGif.hide {
            display: none;
        }
        #loadinggif.show
        {
        left : 50%;
        top : 60%;
        position : absolute;
        z-index : 101;
        width : 32px;
        height : 32px;
        margin-left : -16px;
        margin-top : -16px;
        }
        div.content {
        width : 1000px;
        height : 1000px;
        }

        #divAd {
            /* margin-top:200px; */
            margin-left:50px;
        }

        @media screen and (max-width: 800px) {
            .searchHeader {
                display: none;
            }

            #mobileSearchHeader {
                display: block;
            }

            #trendingTopicsDiv {
                display: none;
            }

            /* Testing */
            /* #divLoadingGifMobile {
                display: block;
            } */



            /* #divLoadingGif.show {
            display : block;
            position : fixed;
            z-index: 100;
            background-image : url('includes/images/loading.gif');
            background-color : #fff;
            opacity : 1.0;
            background-repeat : no-repeat;
            background-position : inherit;
            left : 25%;
            bottom : 0;
            right : 0;
            top : 50%;
            margin-top: 50px;
            margin-right: 0 auto;
            margin-left: 0 auto;
            } */
        }
        @media screen and (min-width: 800px) {
            .searchHeader {
                display: block;
            }

            #mobileSearchHeader {
                display: none;
            }

            
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row">
        <div id="topDiv" class="big">
            <div class="col-lg-5">
                <div id="mobileSearchHeader" style="">
                    <h3>Get Summaries Of The News!</h3><br>
                </div>
                <div class="searchHeader">
                    <h3>Summarize articles from top news sites</h3><br>
                </div>
                <form id="searchSourcesForm">
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
                <h3>Summarize articles from a Search</h3><br>
            </div>
            <form id="searchBingForm">
                <div class="form-group">
                    <input type="text" class="form-control input-lg" id="txtSearchBing" placeholder="Enter Search Term..."><br>
                    <input type="button" class="btn btn-primary" id="btnSubmitSearch" value="Search News">
                </div>
            </form> 
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1">&nbsp;</div>
        <!-- <div class="col-lg-6" id="divLoadingGif">
            <img src="includes/images/loading.gif" height="200px"><br>
            <span id="bePatient">Please be patient your results are loading...</span>
        </div> -->
        <div class="col-lg-6" id="divLoadingGifMobile">
            <!-- <img src="includes/images/loading.gif" height="200px"><br> -->
            <span id="bePatient">Please be patient your results are loading...</span>
        </div>
        <div class="col-lg-6" id='displayResultsDiv'>&nbsp;</div>
        <div class="col-lg-3" id="trendingTopicsDiv">
            <div class="panel panel-primary" id="trendingTopicsPanel">
                <div class="panel-heading">
                    <p><h3 id="trendingTopicsHeader">Trending Topics</h3></p>
                </div>
                <div class="panel-body">
                    <div id="trendingTopicsList"></div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-1">&nbsp;</div>
        <div class="col-lg-6" id="divLoadingGif">
            <!-- <img src="includes/images/loading.gif" height="200px"><br> -->
            <div id="divAd">
                <a href="http://www.tkqlhce.com/click-8425137-11475437" target="_top">
                <img src="http://www.ftjcfx.com/image-8425137-11475437" width="120" height="90" alt="" border="0"/></a>
            </div>

            <span id="bePatient">Please be patient your results are loading...</span>

            
        </div>
    </div>
</div>

<footer class="footer" id="footer">
<span class="text-muted">Powered by NewsApi.org</span>
</footer>

 <script>
$(document).ready(function() {
    // var mobile = false;
    $("#trendingTopicsPanel").hide();

    
    

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
    
    $.post("scrapeBingTrendingTopics.php", 
            function(data) {
                $("#trendingTopicsList").html(data);
                $( ".list-group-item-action" ).on( "click", function() {
                    $("#displayResultsDiv").html("");
                    // $("div#divLoadingGif").addClass('show');
                    $("div#divLoadingGif").addClass('show');

                    var searchWord = $(this).attr("value");

                    $.post("searchAndSummarize.php", 
                    {searchWord: searchWord}, 
                    function(data) {
                        // $("div#divLoadingGif").removeClass('show');
                        $("div#divLoadingGif").removeClass('show');
                        $("div#divLoadingGif").addClass('hide');
                        $("#displayResultsDiv").html(data);
                    })
                    .done(function() {
                        $("#displayResultsDiv").show();
                    })
                    .fail(function() {
                        // $("div#divLoadingGif").removeClass('show');
                        $("div#divLoadingGif").removeClass('show');
                        $("div#divLoadingGif").addClass('hide');
                        alert("Search Failed.  Please Try Again Later.");
                    });
                });
            })
            .done(function() {
                if ($('#trendingTopicsDiv').is(":hidden")) {

                } else {
                    $("#trendingTopicsPanel").show();
                    $("#trendingTopicsDiv").show();
                }
            })
            .fail(function() {
                // $("div#divLoadingGif").removeClass('show');
                alert("Loading Trending Topics Failed");
            });

    $("div#divLoadingGif").removeClass('show');
    // $("#tblDisplayResults").DataTable();

     
    

    $('#txtSearchBing').keypress(function (e) {
        if (e.which == 13 || e.which == 10) {
            e.preventDefault();
            $("#displayResultsDiv").html("");
            $("div#divLoadingGif").addClass('show');
            var searchWord = $("#txtSearchBing").val();
            $.post("searchAndSummarize.php", 
            {searchWord: searchWord}, 
            function(data) {
                $("div#divLoadingGif").removeClass('show');
                $("#displayResultsDiv").html(data);

                $("#displayResultsDiv").show();
            })
            .done(function() {
                // $.post("searchAndSummarize.php", 
                // {searchWord: searchWord},
                // function(data) {
                //     $("div#divLoadingGif").removeClass('show');
                //     $("#displayResultsDiv").html(data);
                // })
                // $("#displayResultsDiv").show();
            })
            .fail(function() {
                $("div#divLoadingGif").removeClass('show');
                alert("Search Failed.  Please Try Again Later.");
            });
            // .always(function() {
            //     getResultsWhenFail(searchWord);
            // });
        }
    });

    $('#btnSubmitSearch').click(function (e) {
            e.preventDefault();
            $("#displayResultsDiv").html("");
            $("#displayResultsDiv").hide();
            $("div#divLoadingGif").addClass('show');
            var searchWord = $("#txtSearchBing").val();
            $.post("searchAndSummarize.php", 
            {searchWord: searchWord}, 
            function(data) {

                $("div#divLoadingGif").removeClass('show');
                $("#displayResultsDiv").html(data);

                $("#displayResultsDiv").show();
            })
            .done(function() {
                // $.post("searchAndSummarize.php", 
                // {searchWord: searchWord},
                // function(data) {
                //     $("div#divLoadingGif").removeClass('show');
                //     $("#displayResultsDiv").html(data);
                // })
                // $("#displayResultsDiv").show();
            })
            .fail(function() {
                $("div#divLoadingGif").removeClass('show');
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
            $("div#divLoadingGif").addClass('show');
            var source = $("#selectSource").val();
            // alert(source);
            $.post("getCurrentEvents.php", 
            {source: source}, 
            function(data) {
                // $.post("searchWord.php",
                // {searchWord: searchWord},
                // function(data) {
                //     $("div#divLoadingGif").removeClass('show');
                //     $("#displayResultsDiv").html(data);
                // })
            })
            .done(function(data) {
                
                $("div#divLoadingGif").removeClass('show');
                $("#displayResultsDiv").html(data);
                $("#displayResultsDiv").show();
            })
            .fail(function() {
                $("div#divLoadingGif").removeClass('show');
                alert("Search Failed.  Please Try Again Later.");
            });
            // .always(function() {
            //     getResultsWhenFail(searchWord);
            // });
    });

    
    
});

</script>
<!-- <script type="text/javascript" language="javascript" src="http://www.jdoqocy.com/placeholder-28523489?target=_top&mouseover=N"></script> -->
</body>
</html>