<?php
	require("config.php");

	if(!empty($_POST)){
		//Check username input
		$q_checkusername = "SELECT 1 FROM User WHERE username = :user";
		$query_params = array(
			':user' => $_POST['username']
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

		//Check email input
		$q_checkemail = "SELECT 1 FROM User WHERE email = :email";
		$query_params = array(
			':email' => $_POST['email']
		);
		try{
			$stmt = $db->prepare($q_checkemail);
			$result = $stmt->execute($query_params);
		} catch(PDOException $e){
			$response["success"] = 0;
			$response["message"] = "Database Error 1" . $e->getMessage();
			die(json_encode($response));
		}

		$row = $stmt->fetch();

		if($row){
			$response["success"] = 2;
			$response["message"] = "This email is already registered";
			die(json_encode($response));
		}
		
		//generate hash to activate account
		$hash = md5(rand(0,1000));
		
		//Create new user in User table
		$q_insert = "INSERT INTO User (email, username, password, hash, active) VALUES (:email, :user, :pass, :hash, '0')";
		$query_params = array(
			':email' => $_POST['email'],
			':user' => $_POST['username'],
			':pass' => md5($_POST['password']),
			':hash' => $hash
		);
		try{
			$stmt = $db->prepare($q_insert);
			$result = $stmt->execute($query_params);
		} catch(PDOException $e){
			$response["success"] = 0;
			$response["message"] = "Database error 2" . $e->getMessage();
			die(json_encode($response));
		}
		
		//Send verification email
                $subject = 'Crime Zone | Sign Up Verification Email';
		$message = '
			Thanks for signing up to CRIME ZONE
			
			Your account has been created, you can login with your email after you activated your account.
			Please click the link below to activate your account:
			------------------------------------------------------------
			http://www.crimezone.besaba.com/verify.php?email='.$email.'&hash='.$hash.'
			------------------------------------------------------------
			
		';
		
		$headers = 'admin@crimezone.besaba.com' . "\r\n";
		mail($_POST['email'], $subject, $message, $headers);
                $response["masuk"] = "masuk sini";

		$response["email"] = $_POST['email'];
                $response["hash"] = $hash;
		$response["success"] = 3;
		$response["message"] = "Account successfully created";
                die(json_encode($response));
	}

	else{
	?>

	<h1>Test Register</h1>
	<form action="register.php" method="post">
		<table>
			<tr>
				<td>Email</td>
				<td><input type="email" name="email" value=""></td>
			</tr>
			<tr>
				<td>Username</td>
				<td><input type="text" name="username" value=""></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="password" name="password" value=""></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Register"></td>
			</tr>
		</table>
	</form>	
	<?php

	}
?>				
			