<?php
	require("config.php");

	if(!empty($_POST)){
		//Query to get user data from database
		$query = "SELECT username, email, firstname, lastname, join_date, sex, occupation, password FROM User WHERE email = :user";
		$query_params = array(
			':user' => $_POST['user']
		);
		try{
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $e){
			$response["success"] = 0;
			$response["message"] = "Database error" . $e->getMessage();
			die(json_encode($response));
		}

		$login_success = false;

		$row = $stmt->fetch();
		if($row){
			if(md5($_POST['password']) == $row['password']){
				$login_success = true;
			}
		}
		else{
                        $response["success"] = 1;
			$response["message"] = "email haven't been registered";
			die (json_encode($response));
		}

		if($login_success){
			$response["success"] = 2;
			$response["message"] = "Login successful!";
			$response["username"] = $row["username"];
			$response["email"] = $row["email"];
			$response["firstname"] = $row["firstname"];
			$response["lastname"] = $row["lastname"];
			$response["join_date"] = $row["join_date"];
			$response["sex"] = $row["sex"];
			$response["occupation"] = $row["occupation"];
			print json_encode($response);
		}
		else {
			$response["success"] = 1;
			$response["message"] = "invalid password";
			die(json_encode($response));
		}
	}

	else{
	?>

	<h1>Test Login</h1>
	<form action="login.php" method="post">
		<table>
			<tr>
				<td>Email or Username</td>
				<td><input type="text" name="user" value=""></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="password" name="password" value=""></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Sign in"></td>
			</tr>
		</table>
	</form>	
	<?php

	}
?>				
