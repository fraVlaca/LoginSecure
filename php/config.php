<?php

	// Database Credentials
	define('DB_HOST', 'localhost');
	define('DB_DATABASE', 'Login');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');

	// Email Credentials
	define('SMTP_HOST', 'smtp-mail.outlook.com');
	define('SMTP_PORT', 587);
	define('SMTP_USERNAME', 'francesco.verification@outlook.com');
	define('SMTP_PASSWORD', 'Beta-5991');
	define('SMTP_FROM', 'francesco.verification@outlook.com');
	define('SMTP_FROM_NAME', 'Francesco Login Verification');

	//twilio credentials
	define('TWILIO_ID','ACaee2ac6f8a1a348a7b5efdff8cc93e94');
	define('TWILIO_AUTH_TOKEN', 'df3b7c059c264355cc6760353e025acb');

	// Global Variables
	define('MAX_LOGIN_ATTEMPTS_PER_HOUR', 5);
	define('MAX_EMAIL_VERIFICATION_REQUESTS_PER_DAY', 1000);
	define('MAX_PASSWORD_RESET_REQUESTS_PER_DAY', 3);
	define('PASSWORD_RESET_REQUEST_EXPIRY_TIME', 60*60);
	define('CSRF_TOKEN_SECRET', '<change me to something random>');
	define('VALIDATE_EMAIL_ENDPOINT', 'http://localhost/SecureLogin/validate'); #Do not include trailing /
	define('RESET_PASSWORD_ENDPOINT', 'http://localhost/SecureLogin/resetpassword'); #Do not include trailing /

	// Code we want to run on every page/script
	date_default_timezone_set('UTC'); 
	error_reporting(0);
	session_set_cookie_params(['samesite' => 'Strict']);
	session_start();
	
