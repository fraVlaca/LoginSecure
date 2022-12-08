<?php
	require_once 'config.php';

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'PHPMailer-master/src/Exception.php';
	require 'PHPMailer-master/src/PHPMailer.php';
	require 'PHPMailer-master/src/SMTP.php';

	require 'Twilio/src/Twilio/autoload.php';
	use \Twilio\Rest\Client;

	function connect() {
		$C = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		if($C->connect_error) {
			return false;
		}
		return $C;
	}



	function sqlSelect($C, $query, $format = false, ...$vars) {
		$stmt = $C->prepare($query);
		if($format) {
			$stmt->bind_param($format, ...$vars);
		}
		if($stmt->execute()) {
			$res = $stmt->get_result();
			$stmt->close();
			return $res;
		}
		$stmt->close();
		return false;
	}

	function sqlInsert($C, $query, $format = false, ...$vars) {
		$stmt = $C->prepare($query);
		if($format) {
			$stmt->bind_param($format, ...$vars);
		}
		if($stmt->execute()) {
			$id = $stmt->insert_id;
			$stmt->close();
			return $id;
		}
		$stmt->close();
		return -1;
	}

	function sqlInsertWithBlob($C, $query, $format = false, $blob, ...$vars){
		$stmt = $C->prepare($query);
		if($format) {
			$stmt->bind_param($format, ...$vars);
		}
		$stmt->send_long_data(4, $blob);
		if($stmt->execute()) {
			$id = $stmt->insert_id;
			$stmt->close();
			return $id;
		}
		$stmt->close();
		return -1;
	}

	function sqlUpdate($C, $query, $format = false, ...$vars) {
		$stmt = $C->prepare($query);
		if($format) {
			$stmt->bind_param($format, ...$vars);
		}
		if($stmt->execute()) {
			$stmt->close();
			return true;
		}
		$stmt->close();
		return false;
	}


	function createToken() {
		$seed = urlSafeEncode(random_bytes(8));
		$t = time();
		$hash = urlSafeEncode(hash_hmac('sha256', session_id() . $seed . $t, CSRF_TOKEN_SECRET, true));
		return urlSafeEncode($hash . '|' . $seed . '|' . $t);
	}

	function validateToken($token) {
		$parts = explode('|', urlSafeDecode($token));
		if(count($parts) === 3) {
			$hash = hash_hmac('sha256', session_id() . $parts[1] . $parts[2], CSRF_TOKEN_SECRET, true);
			if(hash_equals($hash, urlSafeDecode($parts[0]))) {
				return true;
			}
		}
		return false;
	}

	function urlSafeEncode($m) {
		return rtrim(strtr(base64_encode($m), '+/', '-_'), '=');
	}

	function urlSafeDecode($m) {
		return base64_decode(strtr($m, '-_', '+/'));
	}

	function sendEmail($to, $toName, $subj, $msg) {
		$mail = new PHPMailer(true);
		try {
	    //Server settings
	    $mail->isSMTP();
	    $mail->Host       = SMTP_HOST;
	    $mail->SMTPAuth   = true;
	    $mail->Username   = SMTP_USERNAME;
	    $mail->Password   = SMTP_PASSWORD;
	    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	    $mail->Port       = SMTP_PORT;

	    //Recipients
	    $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
	    $mail->addAddress($to, $toName);

	    // Content
	    $mail->isHTML(true);
	    $mail->Subject = $subj;
	    $mail->Body    = $msg;

	    $mail->send();
	    return true;
		} 
		catch(Exception $e) {
			return false;
		}
	}

	function sendPinSms($phoneNum, $pin){
		try{
			$client = new Client(TWILIO_ID, TWILIO_AUTH_TOKEN);
			$mex = "Your 2-factor authentication code is: ". $pin;
			$message = $client->messages->create(
				$phoneNum,
				array(
					"messagingServiceSid" => "MG51896148b0685e754f11943009db02cb", 
					"body" => $mex
				)
			);
			return true;
		} catch(Exception $e) {
			return false;
		}
	}
