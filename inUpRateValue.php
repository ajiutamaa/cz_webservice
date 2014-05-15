<?php
	require("config.php");

	if(!empty($_POST)){
  $uname = $_POST['username'];
  $reportID = $_POST['reportID'];

  $q_userID = "SELECT userID FROM User WHERE username=:username";
  $query_params = array(
			':username' => $uname,
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
    //echo "userID ".$userID;
  }
  else  echo "userID is not exist";
  
  $q_reportID = "SELECT reportID FROM Crime_Report WHERE reportID =:reportID";
  $query_params = array(
			':reportID' => $reportID,
	);
  try{
		$stmt = $db->prepare($q_reportID);
		$result = $stmt->execute($query_params);
	} catch(PDOException $e){
		$response["success"] = 0;
		$response["message"] = "Database Error 0." . $e->getMessage();
		die(json_encode($response));
	}
  $row = $stmt->fetch();
  if(!empty($row)) {
      $q_cekRateVal = "SELECT rate_value FROM Rate WHERE userID = :userID AND reportID = :reportID";
      $query_params = array(
          ':userID' => $userID,
          ':reportID' => $reportID,
      );
      try{
        $stmt = $db->prepare($q_cekRateVal);
        $result = $stmt->execute($query_params);
      } catch(PDOException $e){
        $response["success"] = 0;
        $response["message"] = "Database Error 1." . $e->getMessage();
        die(json_encode($response));
      }
      $row = $stmt->fetch();
      if(!empty($row)) {
        //echo "the value is exist ";

        $q_rateVal = "UPDATE Rate SET rate_value = :rateVal WHERE userID = :userID AND reportID = :reportID";
      }
      else {
        $q_rateVal = "INSERT INTO Rate VALUES (:userID, :reportID, :rateVal)";
      }
      $query_params = array(
          ':userID' => $userID,
          ':reportID' => $reportID,
          ':rateVal' => $_POST['rateVal'],
      );
      try{
        $stmt = $db->prepare($q_rateVal);
        $result = $stmt->execute($query_params);
      } catch(PDOException $e){
        $response["success"] = 0;
        $response["message"] = "Database Error 2." . $e->getMessage();
        die(json_encode($response));
      }

      $q_avgRate = "SELECT avg(rate_value) as avgRate FROM Rate WHERE reportID = :reportID";
      $query_params = array(
            ':reportID' => $reportID,
      );
      try{
        $stmt = $db->prepare($q_avgRate);
        $result = $stmt->execute($query_params);
      } catch(PDOException $e){
        $response["success"] = 0;
        $response["message"] = "Database Error 3." . $e->getMessage();
        die(json_encode($response));
      }
      $row = $stmt->fetch();
      if(!empty($row)) {
        $avgRating = $row['avgRate'];
        //echo "average rate: ".$avgRating;
      }
      else  echo "no Average Rate";

    if(ceil($avgRating)-$avgRating > 0.75) $avgRating = floor($avgRating);
    elseif (ceil($avgRating)-$avgRating > 0.25) $avgRating = ceil($avgRating)-0.5;
    else $avgRating = ceil($avgRating);

    //echo " rounding average dummy rate: ".$avgRating;

    //echo $avgRating;

      $q_insertAvgRating = "UPDATE Crime_Report SET avg_rating = :avgRating WHERE reportID = :reportID";
      $query_params = array(
            ':avgRating' => $avgRating,
            ':reportID' => $reportID,
      );
      try{
        $stmt = $db->prepare($q_insertAvgRating);
        $result = $stmt->execute($query_params);
      } catch(PDOException $e){
        $response["success"] = 0;
        $response["message"] = "Database Error 4." . $e->getMessage();
        die(json_encode($response));
      }
    $newAvgRating["avg_rating"] = $avgRating;
    print json_encode($newAvgRating);

    //print json.encode($avgRating);
  }
  else  echo "reportID is not exist";


}
	else{
	?>

	<h1>Test Insert/Update Rate Value</h1>
	<form action="rating.php" method="post">
		<table>
			<tr>
				<td>username</td>
				<td><input type="text" name="username" value=""></td>
			</tr>
			<tr>
				<td>report ID</td>
				<td><input type="text" name="reportID" value=""></td>
			</tr>
      <tr>
				<td>Rate Value</td>
				<td><input type="text" name="rateVal" value=""></td>
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
