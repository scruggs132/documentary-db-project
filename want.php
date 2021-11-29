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
  $remove_stmt = "DELETE FROM wantToWatchList WHERE docID=? AND userID=?;";
  if($remove_watch = mysqli_prepare($link, $remove_stmt)){
    mysqli_stmt_bind_param($remove_watch, "ii", $watchID_param, $userID_param);
    $watchID_param = intval($_POST["removeID"]);
    $userID_param = $_SESSION["userID"];
    if(!mysqli_stmt_execute($remove_watch)){
       echo "Failed to remove. Try again!";
    }
  }
}
if (isset($_POST["priority"])) {
  $priority_stmt = "UPDATE wantToWatchList SET priority= ? WHERE docID=? AND userID=?;";
  if($priority_watch = mysqli_prepare($link, $priority_stmt)){
    mysqli_stmt_bind_param($priority_watch, "iii", $priority_param, $watchID_param, $userID_param);
    $priority_param = intval($_POST["priority"]);
    $watchID_param = intval($_POST["docID"]);
    $userID_param = $_SESSION["userID"];
    if(!mysqli_stmt_execute($priority_watch)){
       echo "Failed to remove. Try again!";
    }
  }
}
if (isset($_POST["switchID"])) {
  $insert_watched_stmt = "INSERT INTO watchedList(docID, userID, dateWatched) VALUES (?, ?, ?)";
  if($insert_watched = mysqli_prepare($link, $insert_watched_stmt)){
    mysqli_stmt_bind_param($insert_watched, "iis", $watchedID_param, $userID_param, $date_param);
    $watchedID_param = intval($_POST["switchID"]);
    $userID_param = $_SESSION["userID"];
    $date_param = "00/00/0000";
    if(!mysqli_stmt_execute($insert_watched)){
      echo "Failed to add documentary to Watched List. Try again!";
    }
  }
  $add_stmt = "Add wantToWatchList SET priority= ? WHERE docID=? AND userID=?;";
  $remove_stmt = "DELETE FROM wantToWatchList WHERE docID=? AND userID=?;";
  if($remove_watch = mysqli_prepare($link, $remove_stmt)){
    mysqli_stmt_bind_param($remove_watch, "ii", $watchID_param, $userID_param);
    $watchID_param = intval($_POST["switchID"]);
    $userID_param = $_SESSION["userID"];
    if(!mysqli_stmt_execute($remove_watch)){
       echo "Failed to remove from Watch List. Try again!";
    }
  }

}
if (isset($_POST["order"]) && $_POST["order"] == "ASC"){
    $res_query_watch     = "select * from wantToWatchList NATURAL JOIN documentaryTitleYear NATURAL JOIN documentaryTitleOverview NATURAL JOIN documentaryInfo where userID =" . $_SESSION["userID"] . " ORDER BY priority;";
}else if(isset($_POST["order"]) && $_POST["order"] == "DES"){
    $res_query_watch     = "select * from wantToWatchList NATURAL JOIN documentaryTitleYear NATURAL JOIN documentaryTitleOverview NATURAL JOIN documentaryInfo where userID =" . $_SESSION["userID"] . " ORDER BY priority DESC;";
}else{
    $res_query_watch     = "select * from wantToWatchList NATURAL JOIN documentaryTitleYear NATURAL JOIN documentaryTitleOverview NATURAL JOIN documentaryInfo where userID =" . $_SESSION["userID"] . ";";   
}
$res_watch           = $link -> query($res_query_watch);
if ($res_watch === false) {
  die("MySQL database failed");
}
$data_watch = $res_watch->fetch_all(MYSQLI_ASSOC);
if (isset($data_watch)) {
    $watchList = count($data_watch);
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
        <form action ="want.php" method ="post">
            <input type="hidden" name="order" value = "ASC"></input>
            <button type="submit" class="btn btn-success">Ascending By Priority</button>
        </form>
        <br>
        <form action ="want.php" method ="post">
            <input type="hidden" name="order" value = "DES"></input>
            <button type="submit" class="btn btn-success">Descending By Priority</button>
        </form>
    <?php if(count($data_watch)){
 foreach ($data_watch as $doc) {
  ?>
  <div style="margin-left: 25%">
        <div class="card" style="width: 66%">
        <div class="card-body">
            <h5 class="card-title"><?=$doc["title"]?></h5>
            <h6 class="card-subtitle mb-2"><?=$doc["year"]?></h7>
            <p class="card-text-center"><?=$doc["overview"]?></p>
            <p class="card-text"><strong>Average Rating: <?=$doc["averageRating"]?></strong></p>
            <p class="card-text"><strong>Current Watch Priority: <?=$doc["priority"]?></strong></p>
            <form action="want.php" method="post">
                <?php if(isset($_POST["order"])){ ?>
                    <input type="hidden" name="order" value=<?=$_POST["order"]?>> </input>
                <?php } ?>
                <input type="hidden" value=<?=$doc["docID"]?> name="removeID"></input>
                <button type="submit" class="btn btn-danger">Remove from Watch list</button>
            </form>
            <br>
            <form action="want.php" method="post">
                <?php if(isset($_POST["order"])){ ?>
                    <input type="hidden" name="order" value=<?=$_POST["order"]?>> </input>
                <?php } ?>
                <input type="hidden" value=<?=$doc["docID"]?> name="switchID"></input>
                <button type="submit" class="btn btn-success">Switch to Watched List!</button>
            </form>
            <br>
            <form action="want.php" method="post">
                <?php if(isset($_POST["order"])){ ?>
                    <input type="hidden" name="order" value=<?=$_POST["order"]?>> </input>
                <?php } ?>
                <input type="hidden" value=<?=$doc["docID"]?> name="docID"></input>
                <input type="number" name="priority" max = 10 min = 1 value=<?=$doc["priority"]?> ></input>
                <button type="submit" class="btn btn-primary">Update Priority Value</button>                
            </form>
            <br>
            <!-- <form action="detailed.php" method="post" class="mb-2 text-center">
                <input type="hidden" value=<?=$db["docID"]?> name="reviews"></input>
                <button type="submit" class="btn btn-primary">Reviews</button>
            </form> -->
        </div>
        </div>
</div>
        <?php }
    }else{ ?>
            <h4>No documentaries added.</h4>
        <?php
        }
        ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  </body>
  </html>
