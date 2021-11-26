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
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ text-align: center; }
        .wrapper{ width: 450px; padding: 5px; }
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
                        <a class="nav-link active"  href="index.php">Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="watched.php">My Watched List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="want.php">My Want to Watch List</a>
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
    <!-- <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1> -->
    <br>
    <h2>Search for a Documentary</h2>
    <!-- Search Bar -->
    <div class="col-md-8 mx-auto">
    <form class="mb-3">
      <div class="mb-3 text-center">
        <br>
        <input class="form-control me-2 text-center" type="search" placeholder="Type in a Documentary" aria-label="Search">
        <br>
        <!-- for find documentary button, have method for making php and html for the searched movie appear -->
        <a class="btn-lg btn-success my-auto" type="submit">Find Documentary</a>
      </div>
    </form>
  </div>

  <!-- html that should be hidden until a user searches for something (should prob be in the php method) -->
  <div style="border:2px solid black; margin-top:20px; margin-left:15%; margin-right:15%;"> <!--div for each documentary...if there are multiple the div could repeat or something  -->
    <img src="..." alt="documentary image" class="pull-left mr-2">
    <h2>Synopsis: </h2> <!-- text should appear next to the image (image on left, text on right)-->
    <p></p> <!-- Place synopsis in here -->
    <h2>Reviews: </h2>
    <p></p> <!-- Place reviews -->
    <a href="writeReview.php" class="btn btn-primary text-center">Add a Review</a>
    <!-- Either have users directly add to respective list when click on button (use php) and remove the link to respective page or just have them add it on the respective page -->
    <a href="watched.php" class="btn btn-secondary text-center">Add to My Watched List</a>
    <a href="want.php" class="btn btn-success text-center">Add to Want to Watch List</a>
    <br>
    <br>
  </div>
</body>
</html>
