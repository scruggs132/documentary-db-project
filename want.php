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
                        <a class="nav-link" href="watched.php">Watched List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="want.php">Want to Watch List</a>
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
    <h1>My Want to Watch List</h1>
    <!-- Could have button below here redirect to index.php to add, could just paste the add movie php code here, or could just take this button out entirely and have user only add something on the index.php page-->
    <a class="btn btn-success text-center">Add to My Want to Watch List</a>
    <!-- div for each documentary in user's want to watch list (should repeat for each probably)-->
    <div style="border:2px solid black; margin-top:20px; margin-left:15%; margin-right:15%;"> <!--div for each documentary...if there are multiple the div could repeat or something  -->
      <img src="..." alt="documentary image" class="pull-left mr-2">
      <h2>Synopsis: </h2> <!-- text should appear next to the image (image on left, text on right)-->
      <p></p> <!-- Place synopsis in here -->
      <h2>Reviews: </h2>
      <p></p> <!-- Place reviews -->
      <a href="writeReview.php" class="btn btn-primary text-center">Add a Review</a>
      <!-- Either have users directly add to respective list when click on button (use php) and remove the link to respective page or just have them add it on the respective page -->
      <br>
      <br>
    </div>
  </body>
  </html>
