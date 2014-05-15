<?php
	require("config.php");

	if(!empty($_POST)){
$q_userID = "SELECT userID FROM User WHERE username=:username";
$query_params = array(
			':username' => $_POST['username'],
	);
  try{
		$stmt = $db->prepare($q_userID);
		$result = $stmt->execute($query_params);
	} catch(PDOException $e){
		$response["success"] = 0;
		$response["message"] = "Database Error 0." . $e->getMessage();
		die(json_encode($response));
	}
  $row = $stmt->fetch();
  if(!empty($row)) {
    $userID = $row['userID'];
    echo "userID ".$userID;
  }
  else  echo "userID is empty";


		//Create new report in Crime_Report table
		$q_insert = "INSERT INTO Crime_Report (userID, data_created, title, time, description)
                VALUES (:userID, :dreported, :title, :time, :description)";
		$query_params = array(
			':title' => $_POST['title'],
      ':userID' => $userID,
      ':dreported' => $_POST['dreported'],
			':time' => $_POST['time'],
      ':description' => $_POST['description'],
		);
		try{
			$stmt = $db->prepare($q_insert);
			$result = $stmt->execute($query_params);
		} catch(PDOException $e){
			$response["success"] = 0;
			$response["message"] = "Database error 1." . $e->getMessage();
			die(json_encode($response));
		}


  $q_curID = "SELECT MAX(A.reportID) as lastID FROM (SELECT reportID FROM Crime_Report WHERE userID=:userID) as A";
  $query_params = array(
			':userID' => $userID,
	);
    try{
      $stmt = $db->prepare($q_curID);
      $result = $stmt->execute($query_params);
    } catch(PDOException $e){
      $response["success"] = 0;
      $response["message"] = "Database Error 2." . $e->getMessage();
      die(json_encode($response));
    }
    $row = $stmt->fetch();
  if(!empty($row)) {
    $currentID = $row['lastID'];
    echo " curID ".$currentID;
  }
  else  echo "lastID is empty";

    $q_insert2 = "INSERT INTO Crime_Location(reportID, x_coordinate, y_coordinate)
                VALUES (:ID, :lat, :long)";
		$query_params = array(
      ':ID' => $currentID,
			':lat' => $_POST['lat'],
      ':long' => $_POST['long'],
		);
		try{
			$stmt = $db->prepare($q_insert2);
			$result = $stmt->execute($query_params);
		} catch(PDOException $e){
			$response["success"] = 0;
			$response["message"] = "Database error 2" . $e->getMessage();
			die(json_encode($response));
		}


if ( $_POST['crimetype1']!= NULL) {
    $q_insertCT1 = "INSERT INTO Crime_Categories (reportID, CategoryName) VALUES (:ID, :crimetype1)";
		$query_params1 = array(
      ':ID' => $currentID,
			':crimetype1' => $_POST['crimetype1'],
		);
}
if ( $_POST['crimetype2']!= NULL) {
    $q_insertCT2 = "INSERT INTO Crime_Categories (reportID, CategoryName) VALUES (:ID, :crimetype2)";
		$query_params2 = array(
      ':ID' => $currentID,
			':crimetype2' => $_POST['crimetype2'],
		);
}
if ( $_POST['crimetype3']!= NULL) {
    $q_insertCT3 = "INSERT INTO Crime_Categories (reportID, CategoryName) VALUES (:ID, :crimetype3)";
		$query_params3 = array(
      ':ID' => $currentID,
			':crimetype3' => $_POST['crimetype3'],
		);
}
if ( $_POST['crimetype4']!= NULL) {
    $q_insertCT4 = "INSERT INTO Crime_Categories (reportID, CategoryName) VALUES (:ID, :crimetype4)";
		$query_params4 = array(
      ':ID' => $currentID,
			':crimetype4' => $_POST['crimetype4'],
		);
}
if ( $_POST['crimetype5']!= NULL) {
    $q_insertCT5 = "INSERT INTO Crime_Categories (reportID, CategoryName) VALUES (:ID, :crimetype5)";
		$query_params5 = array(
      ':ID' => $currentID,
			':crimetype5' => $_POST['crimetype5'],
		);
}
if ( $_POST['crimetype6']!= NULL) {
    $q_insertCT6 = "INSERT INTO Crime_Categories (reportID, CategoryName) VALUES (:ID, :crimetype6)";
		$query_params6 = array(
      ':ID' => $currentID,
			':crimetype6' => $_POST['crimetype6'],
		);
}
if ( $_POST['crimetype7']!= NULL) {
    $q_insertCT7 = "INSERT INTO Crime_Categories (reportID, CategoryName) VALUES (:ID, :crimetype7)";
		$query_params7 = array(
      ':ID' => $currentID,
			':crimetype7' => $_POST['crimetype7'],
		);
}
if ( $_POST['crimetype8']!= NULL) {
    $q_insertCT8 = "INSERT INTO Crime_Categories (reportID, CategoryName) VALUES (:ID, :crimetype8)";
		$query_params8 = array(
      ':ID' => $currentID,
			':crimetype8' => $_POST['crimetype8'],
		);
}
		try{
if (!empty($q_insertCT1)) {
			$stmt = $db->prepare($q_insertCT1);
			$result = $stmt->execute($query_params1);
}if (!empty($q_insertCT2)) {
$stmt = $db->prepare($q_insertCT2);
			$result = $stmt->execute($query_params2);
}if (!empty($q_insertCT3)) {
$stmt = $db->prepare($q_insertCT3);
			$result = $stmt->execute($query_params3);
}if (!empty($q_insertCT4)) {
$stmt = $db->prepare($q_insertCT4);
			$result = $stmt->execute($query_params4);
}if (!empty($q_insertCT5)) {
$stmt = $db->prepare($q_insertCT5);
			$result = $stmt->execute($query_params5);
}if (!empty($q_insertCT6)) {
$stmt = $db->prepare($q_insertCT6);
			$result = $stmt->execute($query_params6);
}if (!empty($q_insertCT7)) {
$stmt = $db->prepare($q_insertCT7);
			$result = $stmt->execute($query_params7);
}if (!empty($q_insertCT8)) {
$stmt = $db->prepare($q_insertCT8);
			$result = $stmt->execute($query_params8);
}
		} catch(PDOException $e){
			$response["success"] = 0;
			$response["message"] = "Database error 1" . $e->getMessage();
			die(json_encode($response));
		}
		$response["success"] = 1;
		$response["message"] = "Report successfully submitted";
	}

	else{
	?>

	<h1>Test Form Report</h1>
	<form action="submitReport.php" method="post">
		<table>
			<tr>
				<td>Title</td>
				<td><input type="text" name="title" value=""></td>
			</tr>
			<tr>
				<td>username</td>
				<td><input type="text" name="username" value=""></td>
			</tr>
      <tr>
				<td>Latitude</td>
				<td><input type="text" name="lat" value=""></td>
			</tr>
<tr>
				<td>Longitude</td>
				<td><input type="text" name="long" value=""></td>
			</tr>
<tr>
				<td>Date Reported</td>
				<td><input type="text" name="dreported" value="dd/mm/yyyy hh:mm"></td>
			</tr>
			<tr>
				<td>Start from</td>
				<td><input type="text" name="start" value="dd/mm/yyyy hh:mm"></td>
			</tr>
      <tr>
				<td>Until</td>
				<td><input type="text" name="end" value="dd/mm/yyyy hh:mm"></td>
			</tr>
			<tr>
				<td>Description</td>
				<td><textarea rows="4" cols="25" name="description" value="">write the chronology here..</textarea></td>
			</tr>
      <tr>
				<td>CrimeType</td>
	  <td><input type="checkbox" name="crimetype1" value=1>Type1<br>
          <input type="checkbox" name="crimetype2" value=2>Type2<br>
          <input type="checkbox" name="crimetype3" value=3>Type3<br>
          <input type="checkbox" name="crimetype4" value=4>Type4<br>
          <input type="checkbox" name="crimetype5" value=5>Type5<br>
          <input type="checkbox" name="crimetype6" value=6>Type6<br>
          <input type="checkbox" name="crimetype7" value=7>Type7<br>
          <input type="checkbox" name="crimetype8" value=8>Type8<br>
        </td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="submit report"></td>
			</tr>
		</table>
	</form>
	<?php

	}
?>
	