<?php error_reporting( E_ALL ); ?>

<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        .articleText {
            font-size: 16;
        }
    </style>
</head>

<body>
 


<br />
<br />  
<div class="container">
    <div class="row">
        <div class="col-lg-4">&nbsp;</div>
        <div class="col-lg-4">
            <form id="searchBingForm">
                <div class="form-group">
                    <input type="text" class="form-control" id="txtSearchBing" placeholder="Query Bing News Articles">
                </div>
                <input type="button" class="btn btn-default" id="btnSubmitBingSearch" value="Search Bing News">
            </form> 
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">&nbsp;</div>
        <div class="col-lg-4" id="divLoadingGif">
            <img src="includes/images/loading.gif">
        </div>
    </div>
    <br><br>
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
    
});

</script>

</body>
</html>