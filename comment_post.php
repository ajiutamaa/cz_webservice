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
		//Query get time
		$q_time = "SELECT NOW() as now";
		$stmt = $db->prepare($q_time);
		$result = $stmt->execute();
		$temp = $stmt->fetch();
		$now = $temp["now"];
		
		$userID = $row['userID'];

		//Create new comment in Comment table
		$q_insert = "INSERT INTO Comment
		VALUES (:reportID, :userID, :content, :now)";
		$query_params = array(
			':userID' => $userID,
			':reportID' => $_POST['reportID'],
			':content' => $_POST['content'],
			':now' => $now
			);
		
		try{
			$stmt = $db->prepare($q_insert);
			$result = $stmt->execute($query_params);
		} catch(PDOException $e){
			$response["success"] = 0;
			$response["message"] = "Database error disini 0." . $e->getMessage();
			die(json_encode($response));
		}

		$response["success"] = 1;
		$response["message"] = "Comment successfully submitted";
		$response["time"] = $now;
                print json_encode($response);
	}
	else {
		$response["success"] = 0;
		$response["message"] = "Failed";
                print json_encode($response);
	}
}

?>
