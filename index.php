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

$watchList = array(0);
$watchedList = array(0);

$res_query_watch     = "select * from wantToWatchList where userID =" . $_SESSION["userID"] . ";";
$res_watch           = $link -> query($res_query_watch);
$res_query_watched     = "select * from watchedList where userID =" . $_SESSION["userID"] . ";";
$res_watched           = $link -> query($res_query_watched);
if ($res_watch === false || $res_watched === false) {
  die("MySQL database failed");
}
$data_watch = $res_watch->fetch_all(MYSQLI_ASSOC);
$data_watched = $res_watched->fetch_all(MYSQLI_ASSOC);

if (isset($data_watch)) {
  foreach ($data_watch as $id){
    $watchList[] = $id["docID"];
  }
}
if (isset($data_watched)) {
  foreach ($data_watched as $id){
    $watchedList[] = $id["docID"];
  }
}
if (isset($_POST["watchID"]) && !in_array($_POST["watchID"], $watchList)) {
  $base_priority = NULL;
  $insert_watch_stmt = "INSERT INTO wantToWatchList(docID, userID, priority) VALUES (?, ?, ?)";
  if($insert_watch = mysqli_prepare($link, $insert_watch_stmt)){
    mysqli_stmt_bind_param($insert_watch, "iii", $watchID_param, $userID_param, $priority_param);
    $watchID_param = intval($_POST["watchID"]);
    $userID_param = $_SESSION["userID"];
    $priority_param = $base_priority;
    if(mysqli_stmt_execute($insert_watch)){
      echo "Successfully added to your Watch List!";
      $watchList[] = $_POST["watchID"];
    }else{
    echo "Failed to Add. Try again!";
    }
  }
}
if (isset($_POST["watchedID"]) && !in_array($_POST["watchedID"], $watchedList)) {
  $insert_watched_stmt = "INSERT INTO watchedList(docID, userID, dateWatched) VALUES (?, ?, ?)";
  if($insert_watched = mysqli_prepare($link, $insert_watched_stmt)){
    mysqli_stmt_bind_param($insert_watched, "iis", $watchedID_param, $userID_param, $date_param);
    $watchedID_param = intval($_POST["watchedID"]);
    $userID_param = $_SESSION["userID"];
    $date_param = "00/00/0000";
    if(mysqli_stmt_execute($insert_watched)){
      echo "Successfully added to your Watched List!";
      $watchedList[] = $_POST["watchedID"];
    }else{
      echo "Failed to add. Try again!";
    }
  }
}
if (isset($_POST["search"])) {
  $search_stmt = "select docID, title, overview from documentaryTitleOverview NATURAL JOIN documentaryTitleYear where title LIKE ?;";
  if($search = mysqli_prepare($link, $search_stmt)){
    mysqli_stmt_bind_param($search, "s", $search_param);
    $search_param = "%" . strtoupper($_POST["search"]) ."%";
    if(mysqli_stmt_execute($search)){
        $data = array();
        mysqli_stmt_bind_result($search, $docID, $title, $overview);
        mysqli_stmt_fetch($search);
        while (mysqli_stmt_fetch($search)) {
          $data[] = array(
            "docID" => $docID,
            "title" => $title,
            "overview" => $overview
          );
          $no_list = false;
        }
    }
    }else{
      echo "Failed to search. Try again!";
    }
  }

  
$message = "";

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
    <br>
    <h2>Search for a Documentary</h2>
    <!-- Search Bar -->
    <div class="col-md-8 mx-auto">
      <div class="mb-3 text-center">
        <br>
        <form action="index.php" method="post" class="mb-2 text-center">
          <input class="form-control" id = "search" name = "search" type="text" placeholder="Type in a Documentary" aria-label="Search"></input>
          <br>
          <button type="submit"  class="btn-lg btn-success my-auto">Find Documentary</button>
        </form>
        <!-- for find documentary button, have method for making php and html for the searched movie appear -->
      </div>
  </div>
  <?php if (isset($no_list) && !$no_list) {
 foreach ($data as $db) {
    $selected_watch= in_array($db["docID"], $watchList);
    $selected_watched = in_array($db["docID"], $watchedList);
  ?>
  <div style="margin-left: 25%">
        <div class="card" style="width: 66%">
        <div class="card-body">
            <div class="d-flex">
              <h5 class="card-title"><?=$db["title"]?></h5>
            </div>
            <p class="card-text-center"><?=$db["overview"]?></h6>
            <form action="index.php" method="post">
                <input type="hidden" value=<?=$_POST["search"]?>  name="search"></input>
                <input type="hidden" value=<?=$db["docID"]?> name="watchID"></input>
                <?php if (!$selected_watch && !$selected_watched) {?>
                <button type="submit" class="btn btn-primary">Add to Watch list</button>
                
                <?php } else if ($selected_watch){?>
                <a class="btn btn-secondary">Already Added to Watch List!</a>
                <?php }?>
            </form>
            <br>
            <form action="index.php" method="post">
                <input type="hidden" value=<?=$_POST["search"]?>  name="search"></input>
                <input type="hidden" value=<?=$db["docID"]?> name="watchedID"></input>
                <?php if (!$selected_watched && !$selected_watch) {?>
                <button type="submit" class="btn btn-primary">Add to Watched list</button>
                <?php } else if ($selected_watched) {?>
                <a class="btn btn-secondary">Already Watched!</a>
                <?php }?>
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
      }else if(isset($data)){
          echo "No documentaries matched that search.";
        } ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body>
</html>
