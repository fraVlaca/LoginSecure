<?php
	require_once 'utils.php'; 
	
	if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['csrf_token']) && validateToken($_POST['csrf_token'])) {
		$email = $_POST['email'];
		$password = $_POST['password'];
		$C = connect();
		if($C) {
			$hourAgo = time() - 60*60;
			$res = sqlSelect($C, 'SELECT users.id AS id,password,verified,phoneNumber,role,COUNT(loginattempts.id) FROM users LEFT JOIN loginattempts ON users.id = user AND timestamp>? WHERE email=? GROUP BY users.id', 'is', $hourAgo, $email);
			if($res && $res->num_rows === 1) {
				$user = $res->fetch_assoc();
				if($user['verified']) {
					if($user['COUNT(loginattempts.id)'] <= MAX_LOGIN_ATTEMPTS_PER_HOUR) {
						if(password_verify($password, $user['password'])) {
							$pin = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
							$pinHash = password_hash($pin, PASSWORD_DEFAULT);
							if(sqlInsert($C, 'INSERT INTO requests VALUES (NULL, ?, ?, ?, 3)', 'isi', $user['id'], $pinHash, time()) !== -1) {
								$_SESSION['userID'] = $user['id'];
								$_SESSION['role'] = $user['role'];
								if(sendPinSms($user['phoneNumber'], $pin)) {
									echo 0;
								} else {
									echo 6;
								}
							} else {
								echo 5;
							};
						}
						else {
							$id = sqlInsert($C, 'INSERT INTO loginattempts VALUES (NULL, ?, ?, ?)', 'isi', $user['id'], $_SERVER['REMOTE_ADDR'], time());
							if($id !== -1) {
								echo 1;
							}
							else {
								echo 2;
							}
						}
					}
					else {
						echo 3;
					}
				}
				else {
					echo 4;
				}

				$res->free_result();
			}
			else {
				echo 1;
			}
			$C->close();
		}
		else {
			echo 2;
		}
	}
	else {
		echo 1;
	}