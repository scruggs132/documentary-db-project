<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
/*
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviews</title>
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
                        <a class="nav-link" href="watched.php">Watched List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="want.php">Want to Watch List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="writeReview.php">Write a Review</a>
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
    <h1>My Reviews</h1>
    <!-- div for each documentary in user's want to watch list (should repeat for each probably)-->
    <div style="border:2px solid black; margin-top:20px; margin-left:15%; margin-right:15%;"> <!--div for each documentary...if there are multiple the div could repeat or something  -->
      <!--Use documentary title and year to match to unique docID -->
            <form>
              <div class=" form-group mb-3 text-center" style="margin-left:20px; margin-right:20px;">
                <br>
                <input class="form-control me-2 required" type="search" placeholder="Type Documentary Title" aria-label="Search">
                <br>
                <input class="form-control me-2 required" type="search" placeholder="Type Documentary Year" aria-label="Search">
                <br>
                <!-- for find documentary button, have method for making php and html for the searched movie appear -->
                <a class="btn-lg btn-success my-auto" type="submit">Find Documentary</a>
              </div>
            <div class="form-group" style="margin-left:20px; margin-right:20px;">
            <!-- <label for="exampleFormControlTextarea1"></label> -->
            <textarea class="form-control required"  id="review" rows="5" placeholder="Write Review Here"></textarea>
            <br>
            <a class="btn-lg btn-success my-auto" type="submit">Submit Review</a>
            </div>
            </form>

      <br>
      <br>
    </div>


  </body>
  </html>
