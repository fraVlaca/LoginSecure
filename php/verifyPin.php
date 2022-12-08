<?php 
	require_once 'utils.php'; 
	if(isset($_POST['csrf_token']) && validateToken($_POST['csrf_token'])) {
		if(isset($_SESSION['userID']) && isset($_SESSION['role'])) {
			$C = connect();
			if($C) {
				$res = sqlSelect($C, 'SELECT user,hash,timestamp FROM requests WHERE user=? AND type=3 ORDER BY timestamp DESC', 'i', $_SESSION['UserID']);
				if($res) {
					$userRequest = $res->fetch_assoc();
					$tenMinuteAgo = time() - 60 * 10;
					if($UserRequest['timestamp'] > $tenMinuteAgo) {
						if(password_verify($_POST['pin'], $userRequest['hash'])) {
							sqlUpdate($C,'DELETE FROM requests WHERE user=? AND type=3', 'i', $userRequest['user']);
							echo 0;
						}
						else {
							echo 6;
						}
					}
					else {
						echo 5;
					}
					$res->free_result();
				}
				else {
					echo 4;
				}
				$C->close();
			}
			else {
				echo 3;
			}
		} else {
			echo 2;
		}
	} else {
		echo 1; //csrf
	}
