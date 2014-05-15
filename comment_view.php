<?php
require("config.php");

if($_POST['first']){

	$q_checkComment = "SELECT c.userID, username, content, date as time
	FROM Comment c, User u
	WHERE reportID = :reportID AND c.userID = u.userID AND reportID > :limit
	ORDER BY date DESC";
	$query_params = array(
		':reportID' => $_POST['reportID'],
		':limit' => $_POST['first']
		);

	try{
		$stmt = $db->prepare($q_checkComment);
		$res = $stmt->execute($query_params);
	} catch(PDOException $e){
		$response["success"] = 0;
		$response["message"] = "Database Error." . $e->getMessage();
		die(json_encode($response));
	}

	if(!$res){
		$response["success"] = 0;
		$response["message"] = "No report found.";
		die(json_encode($response));
		echo "no success";
	}
	else {
		$result = array();
		while($row = $stmt->fetch())
		{
			$result[] = $row;
		}

		print json_encode($result);
	}
}

else if($_POST['last']){

	$q_checkComment = "SELECT c.userID, username, content, date as time
	FROM Comment c, User u
	WHERE reportID = :reportID AND c.userID = u.userID AND reportID < :limit
	ORDER BY date DESC LIMIT 5";
	$query_params = array(
		':reportID' => $_POST['reportID'],
		':limit' => $_POST['limit']
		);

	try{
		$stmt = $db->prepare($q_checkComment);
		$res = $stmt->execute($query_params);
	} catch(PDOException $e){
		$response["success"] = 0;
		$response["message"] = "Database Error." . $e->getMessage();
		die(json_encode($response));
	}

	if(!$res){
		$response["success"] = 0;
		$response["message"] = "No report found.";
		die(json_encode($response));
		echo "no success";
	}
	else {
		$result = array();
		while($row = $stmt->fetch())
		{
			$result[] = $row;
		}

		print json_encode($result);
	}
}

else {

	$q_checkComment = "SELECT c.userID, username, content, date as time
	FROM Comment c, User u
	WHERE reportID = :reportID AND c.userID = u.userID
	ORDER BY date DESC";
	$query_params = array(
		':reportID' => $_POST['reportID'],
		);

	try{
		$stmt = $db->prepare($q_checkComment);
		$res = $stmt->execute($query_params);
	} catch(PDOException $e){
		$response["success"] = 0;
		$response["message"] = "Database Error." . $e->getMessage();
		die(json_encode($response));
	}

	if(!$res){
		$response["success"] = 0;
		$response["message"] = "No report found.";
		die(json_encode($response));
		echo "no success";
	}
	else {
		$result = array();
		while($row = $stmt->fetch())
		{
			$result[] = $row;
		}

		print json_encode($result);
	}
}
?>
