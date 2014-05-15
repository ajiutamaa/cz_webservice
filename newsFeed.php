<?php
require("config.php");

if($_POST['first']){
  $q_detail = "SELECT cr.title, cr.reportID, usr.username, usr.userID, cr.time, cr.avg_rating, categories.cats
      FROM Crime_Report cr, User usr,
       (SELECT reportID, GROUP_CONCAT(Cast(CategoryName as CHAR) ORDER BY CategoryName ASC SEPARATOR ',') AS cats FROM Crime_Categories GROUP BY reportID) as categories
      WHERE cr.reportID = categories.reportID AND cr.userID = usr.userID AND cr.reportID > :limit ORDER BY cr.reportID DESC
    ";

  $q_params = array(':limit' => $_POST['first']);

  try{
    $stmt = $db->prepare($q_detail);
    $res = $stmt->execute($q_params);
  } catch(PDOException $e){
    $response["success"] = 0;
    $response["message"] = "Database Error." . $e->getMessage();
    die(json_encode($response));
  }
}

else if($_POST['last']){
    $q_detail = "SELECT cr.title, cr.reportID, usr.username, usr.userID, cr.time, cr.avg_rating, categories.cats
      FROM Crime_Report cr, User usr,
       (SELECT reportID, GROUP_CONCAT(Cast(CategoryName as CHAR) ORDER BY CategoryName ASC SEPARATOR ',') AS cats FROM Crime_Categories GROUP BY reportID) as categories
      WHERE cr.reportID = categories.reportID AND cr.userID = usr.userID AND cr.reportID < :limit
      AND cr.reportID > (:limit - 7) ORDER BY cr.reportID DESC
    ";

  $q_params = array(':limit' => $_POST['last']);

  try{
    $stmt = $db->prepare($q_detail);
    $res = $stmt->execute($q_params);
  } catch(PDOException $e){
    $response["success"] = 0;
    $response["message"] = "Database Error." . $e->getMessage();
    die(json_encode($response));
  }
}

else {
  $q_detail = "SELECT cr.title, cr.reportID, usr.username, usr.userID, cr.time, cr.avg_rating, categories.cats
      FROM Crime_Report cr, User usr,
       (SELECT reportID, GROUP_CONCAT(Cast(CategoryName as CHAR) ORDER BY CategoryName ASC SEPARATOR ',') AS cats FROM Crime_Categories GROUP BY reportID) as categories
      WHERE cr.reportID = categories.reportID AND cr.userID = usr.userID ORDER BY cr.reportID DESC LIMIT 10
  ";

  $q_params = array(':limit' => $_POST['first']);

  try{
    $stmt = $db->prepare($q_detail);
    $res = $stmt->execute($q_params);
  } catch(PDOException $e){
    $response["success"] = 0;
    $response["message"] = "Database Error." . $e->getMessage();
    die(json_encode($response));
  }

}

if(!$res){
  $response["success"] = 0;
  $response["message"] = "No report found.";
  die(json_encode($response));
}
else {
  $result = array();

  //source: http://stackoverflow.com/questions/21170992/mysql-query-to-pull-rows-and-print-them-as-json
  while($row = $stmt->fetch())
  {
    $result[] = $row;
  }

  print json_encode($result);    
}
?>