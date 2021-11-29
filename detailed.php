<?php
/** DATABASE SETUP **/
require_once "local-config.php";
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$doc = array();
if(isset($_POST["reviews"])){ 
        $stmt = "select docID, title, overview, year, averageRating from documentaryTitleYear NATURAL JOIN documentaryTitleOverview NATURAL JOIN documentaryInfo where docID = ?;";
        if($stmt_prep = mysqli_prepare($link, $stmt)){
        mysqli_stmt_bind_param($stmt_prep, "i", $docID_param);
        $docID_param = intval($_POST["reviews"]);
        if(mysqli_stmt_execute($stmt_prep)){
            mysqli_stmt_bind_result($stmt_prep, $docID, $title, $overview, $year, $rating);
            mysqli_stmt_fetch($stmt_prep);
            mysqli_stmt_fetch($stmt_prep); 
            $doc = array(
                "docID" => $docID,
                "title" => $title,
                "overview" => $overview,
                "year" => $year,
                "averageRating" => $rating,
            );
    }else{
        echo "Failed to find documentary. Go back to search!";
    }
    }
//     $comm_stmt = "select comment from comments NATURAL JOIN leftUnder where docID = ?;"
//   }
// } else{
//     echo "Failed to find documentary. Go back to search!";
}
// echo $_POST["reviewText"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Want to Watch</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>

<body class="bg-lightYellow">
  <!-- Navigation bar code -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" aria-label="Main Navigation Bar">
        <div class="container-xl">
            <a class="navbar-brand" href="index.php">Documentary Database</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsTop" aria-controls="navbarsTop" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsTop">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link"  href="index.php">Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="watched.php">Watched List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="want.php">Want to Watch List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="writeReview.php">Write a Review</a>
                    </li>
                </ul>
                <span class="navbar-text">
                  Hello, <?= $_SESSION["username"]?>!
                </span>
                <a style="margin-left: 15px" class="btn btn-danger" href="logout.php">Log out</a>
            </div>
        </div>
    </nav>
    <br>
        <div style="margin-left: 25%">
        <div class="card" style="width: 66%">
        <div class="card-body">
            <h5 class="card-title"><?=$doc["title"]?></h5>
            <h6 class="card-subtitle mb-2"><?=$doc["year"]?></h7>
            <p class="card-text-center"><?=$doc["overview"]?></p>
            <p class="card-text"><strong>Average Rating: <?=$doc["averageRating"]?></strong></p> 
        </div>
        </div>
        </div>
        <div class="text-center" style="margin: 8%">
            <h4> Review Section: </h4>
            <form action = detailed.php method="post">
                <input type="hidden" value=<?=$_POST["reviews"]?>  name="reviews"></input>
                <input class="form-control required"  type="text" name="reviewText" placeholder="Write Review Here"></textarea>
                <button type="submit"  class="btn-lg btn-success my-auto">Submit Review</button>
            </form>
        </div>          
        </form> 

