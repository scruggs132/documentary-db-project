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
if (isset($_POST["removeID"]) && $_POST["removeID"] != "") {
  $remove_stmt = "DELETE FROM watchedList WHERE docID=? AND userID=?;";
  if($remove_watch = mysqli_prepare($link, $remove_stmt)){
    mysqli_stmt_bind_param($remove_watch, "ii", $watchID_param, $userID_param);
    $watchID_param = intval($_POST["removeID"]);
    $userID_param = $_SESSION["userID"];
    if(!mysqli_stmt_execute($remove_watch)){
       echo "Failed to remove. Try again!";
    }
  }
}
if (isset($_POST["dateID"])) {
    echo $_POST["date"];
  $date_stmt = "UPDATE watchedList SET dateWatched = ? WHERE docID=? AND userID=?;";
  if($date_watch = mysqli_prepare($link, $date_stmt)){
    mysqli_stmt_bind_param($date_watch, "sii", $date_param, $watchID_param, $userID_param);
    $date_param = $_POST["date"];
    $watchID_param = intval($_POST["dateID"]);
    $userID_param = $_SESSION["userID"];
    if(!mysqli_stmt_execute($date_watch)){
       echo "Failed to add date. Try again!";
    }
  }
}

if (isset($_POST["order"]) && $_POST["order"] == "recent"){
    $res_query_watched     = "select * from watchedList NATURAL JOIN documentaryTitleYear NATURAL JOIN documentaryTitleOverview NATURAL JOIN documentaryInfo where userID =" . $_SESSION["userID"] . " ORDER BY dateWatched DESC;";
}else if(isset($_POST["order"]) && $_POST["order"] == "oldest"){
    $res_query_watched     = "select * from watchedList NATURAL JOIN documentaryTitleYear NATURAL JOIN documentaryTitleOverview NATURAL JOIN documentaryInfo where userID =" . $_SESSION["userID"] . " ORDER BY dateWatched;";
}else{
    $res_query_watched     = "select * from watchedList NATURAL JOIN documentaryTitleYear NATURAL JOIN documentaryTitleOverview NATURAL JOIN documentaryInfo where userID =" . $_SESSION["userID"] . ";";   
}
$res_watched           = $link -> query($res_query_watched);
if ($res_watched === false) {
  die("MySQL database failed");
}
$data_watched = $res_watched->fetch_all(MYSQLI_ASSOC);
if (isset($data_watched)) {
    $watchedList = count($data_watched);
}


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
    <h1>My Watched List</h1>
        <form action ="watched.php" method ="post">
            <input type="hidden" name="order" value = "recent"></input>
            <button type="submit" class="btn btn-success">Recent First</button>
        </form>
        <br>
        <form action ="watched.php" method ="post">
            <input type="hidden" name="order" value = "oldest"></input>
            <button type="submit" class="btn btn-success">Oldest First</button>
        </form>
    <?php if(count($data_watched)){
 foreach ($data_watched as $doc) {
  ?>
  <div style="margin-left: 25%">
        <div class="card" style="width: 66%">
        <div class="card-body">
            <h5 class="card-title"><?=$doc["title"]?></h5>
            <h6 class="card-subtitle mb-2"><?=$doc["year"]?></h7>
            <p class="card-text-center"><?=$doc["overview"]?></p>
            <p class="card-text"><strong>Average Rating: <?=$doc["averageRating"]?></strong></p>
            <?php if($doc["dateWatched"] != "0000-00-00"){ ?>
            <p class="card-text"><strong>Date Watched: <?=$doc["dateWatched"]?></strong></p>
            <?php }else{ 
                ?>
            <p class="card-text"><strong>Add Date Watched Below</strong></p>
            <?php } ?>
            <form action="watched.php" method="post">
                <input type="hidden" value=<?=$doc["docID"]?> name="removeID"></input>
                <button type="submit" class="btn btn-danger">Remove from Watched list</button>
            </form>
            <br>
            <form action="watched.php" method="post">
                <?php if(isset($_POST["order"])){ ?>
                    <input type="hidden" name="order" value=<?=$_POST["order"]?>> </input>
                <?php } ?>
                <input type="hidden" value=<?=$doc["docID"]?> name="dateID"></input>
                <input type="date" name="date" value=<?=$doc["dateWatched"]?> ></input>
                <button type="submit" class="btn btn-primary">Update Date Watched</button>                
            </form>
            <!-- <br>
            <form action="detailed.php" method="post" class="mb-2 text-center">
                <input type="hidden" value=<?=$db["docID"]?> name="reviews"></input>
                <button type="submit" class="btn btn-primary">Reviews</button>
            </form> -->
        </div>
        </div>
</div>
        <?php }
    }else{ ?>
            <h4>No documentaries watched.</h4>
        <?php
        }
        ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  </body>
  </html>
