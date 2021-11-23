<?php
  /** SETUP **/
include('database_variables.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Extra Error Printing
$db = new mysqli($SERVER, $USERNAME, $PASSWORD, $DATABASE); //NOT CREATING NEW DB, does this just set it?

//start fresh documentary info table
$db->query("drop table if exists documentaryInfo;");

//create documentary info table
$db->query("create table documentaryInfo(
            docID int not null unique auto_increment,
            overview TEXT(10000) NOT NULL UNIQUE,
            averageRating int,
            year NOT NULL,
            primary key(docID));");

//start fresh documentaryTitleYear
//tbh I dont totally understand why this table exists -- dont we have the year in the documentary info table??  should we include this?  
$db->query("drop table if exists documentaryTitleYear;");

//create documentary info table
$db->query("create table documentaryTitleYear(
            docID int,
            year YEAR REFERENCES documentaryInfo(year),
            PRIMARY KEY(docID),
            FOREIGN KEY(docID) REFERENCES documentaryInfo(docID),
            title VARCHAR(255) NOT NULL);");





/*
$row = 1; // starts with first row of csv file/ first question
if (($handle = fopen("us-corporate-env-updated.csv", "r")) !== FALSE) { //make sure csv can open
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { //access csv contents
      $num = count($data);
      echo $num;
      print_r($data);
      //$row++;
      $stmt = $db->prepare("insert into corporation (name, industry, cost) values (?,?,?);");
      for ($c=0; $c < $num; $c++){
          if ($c == 1){
            $nameInsert = trim($data[$c]);
          }
          else if ($c == 2){
            $industryInsert = trim($data[$c]);
          }
          else if ($c == 3){
            $costInsert = trim($data[$c]);
        }
    }
      $stmt->bind_param("sss", $nameInsert, $industryInsert, $costInsert);
        if (!$stmt->execute()) {
            echo "Could not add corporation: {$nameInsert}\n";
        }
        $row++;
    }
}
    fclose($handle);
    */
    echo "Setup";

?>