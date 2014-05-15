<?php
require("config.php");

if(!empty($_POST)){
  //Check username input
  $q_checkusername = "SELECT 1 FROM User WHERE username = :user";
  $query_params = array(
    ':user' => $_POST['newUsername']
    );
  try{
    $stmt = $db->prepare($q_checkusername);
    $result = $stmt->execute($query_params);
  } catch(PDOException $e){
    $response["success"] = 0;
    $response["message"] = "Database Error" . $e->getMessage();
    die(json_encode($response));
  }

  $row = $stmt->fetch();

  if($row){
    $response["success"] = 1;
    $response["message"] = "Username is already used";
    die(json_encode($response));
  }

  //Find userID
  $uname = $_POST['username'];
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
  if($row) {
    $userID = $row['userID'];
  }
  else {
    $response["success"] = 0;
    $response["message"] = "Database Error 0." . $e->getMessage();
    die(json_encode($response));
  }

  //username, password, email
  $q_updatePrimeData = "UPDATE User SET username = :setUsername, firstname = :firstname, lastname = :lastname
  WHERE userID = :userID";
  $query_params = array(
   ':setUsername' => $_POST['newUsername'],
   ':firstname' => $_POST['firstname'],
   ':lastname' => $_POST['lastname'],
   ':userID' => $userID,
   );
  try{
    $stmt = $db->prepare($q_updatePrimeData);
    $result = $stmt->execute($query_params);
  } catch(PDOException $e){
    $response["success"] = 0;
    $response["message"] = "Database Error 1." . $e->getMessage();
    die(json_encode($response));
  }
  $response["success"] = 2;
  $response["message"] = "User profile successfully updated";
  die(json_encode($response));
}
else{
	?>

	<h1>Test Update Profile</h1>
	<form action="updateProfile.php" method="post">
		<table>
			<tr>
				<td>old username</td>
				<td><input type="text" name="username" value=""></td>
			</tr>
      <tr>
        <td>new username</td>
        <td><input type="text" name="newUsername" value=""></td>
      </tr>
      <tr>
        <td>firstname</td>
        <td><input type="text" name="firstname" value=""></td>
      </tr>
      <tr>
        <td>lastname</td>
        <td><input type="text" name="lastname" value=""></td>
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
