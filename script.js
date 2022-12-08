

// global functions
function request(url, data, callback) {
	var xhr = new XMLHttpRequest();
	xhr.open('POST', url, true);
	var loader = document.createElement('div');
	loader.className = 'loader';
	document.body.appendChild(loader);
	xhr.addEventListener('readystatechange', function() {
		if(xhr.readyState === 4) {
			if(callback) {
				callback(xhr.response);
			}
			loader.remove();
		}
	});

	var formdata = data ? (data instanceof FormData ? data : new FormData(document.querySelector(data))) : new FormData();

	var csrfMetaTag = document.querySelector('meta[name="csrf_token"]');
	if(csrfMetaTag) {
		formdata.append('csrf_token', csrfMetaTag.getAttribute('content'));
	}

	xhr.send(formdata);
}


// index.php
function logout() {
	request('/SecureLogin/php/logout.php', false, function(data) {
		if(data === '0') {
			window.location = 'login';
		}
	});
}
function deleteAccount() {
	request('/SecureLogin/php/deleteAccount.php', false, function(data) {
		document.getElementById('errs').innerHTML = "";
		var transition = document.getElementById('errs').style.transition;
		document.getElementById('errs').style.transition = "none";
		document.getElementById('errs').style.opacity = 0;
		switch(data) {
			case '0':
				window.location = 'register';
				break;
			case '1':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to delete account. Please try again later.</div>';
				break;
			case '2':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to connect to database. Please try again later.</div>';
				break;
			case '3':
				document.getElementById('errs').innerHTML += '<div class="err">You are not logged in.</div>';
				break;
			case '4':
				document.getElementById('errs').innerHTML += '<div class="err">Invalid CSRF Token... Nice try</div>';
				break;
			default:
				document.getElementById('errs').innerHTML += '<div class="err">An unknown error occurred. Please try again later.</div>';
		}
		setTimeout(function() {
			document.getElementById('errs').style.transition = transition;
			document.getElementById('errs').style.opacity = 1;
		}, 10);
	});
}

// login.php
function login() {
	request('/SecureLogin/php/login.php', '#loginForm', function(data) {
		document.getElementById('errs').innerHTML = "";
		var transition = document.getElementById('errs').style.transition;
		document.getElementById('errs').style.transition = "none";
		document.getElementById('errs').style.opacity = 0;
		switch(data) {
			case '0':
				window.location = '/SecureLogin/pin';
				break;
			case '1':
				document.getElementById('errs').innerHTML += '<div class="err">Incorrect username or password</div>';
				break;
			case '2':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to connect to database. Please try again later.</div>';
				break;
			case '3':
				document.getElementById('errs').innerHTML += '<div class="err">You have exceeded the max number of login attempts per hour. Try again in an hour.</div>';
				break;
			case '4':
				document.getElementById('errs').innerHTML += '<div class="err">Your email has not been validated. Please check your email for a validation link or <a href="/SecureLogin/validate">click here</a> to send another link</div>';
				break;
			case '5':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to set 2FA PIN, please try again later...</div>';
				break;
			case '6':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to send 2FA SMS with PIN, please try again later...</div>';
				break;
			default:
				document.getElementById('errs').innerHTML += '<div class="err">An unknown error occurred. Please try again later.</div>';;
		}
		setTimeout(function() {
			document.getElementById('errs').style.transition = transition;
			document.getElementById('errs').style.opacity = 1;
		}, 10);
	});
}

// register.php
function register() {
	request('/SecureLogin/php/register.php', '#registerForm', function(data) {
		document.getElementById('errs').innerHTML = "";
		var transition = document.getElementById('errs').style.transition;
		document.getElementById('errs').style.transition = "none";
		document.getElementById('errs').style.opacity = 0;
		try {
			data = JSON.parse(data);
			if(!(data instanceof Array)) {throw Exception('bad data');}

			//Show errors to user
			for(var i = 0;i < data.length;++i) {
				switch(data[i]) {
					case 0:
						document.getElementById('errs').innerHTML += '<div>Your account has been created!</div><div>Please validate your email by checking your inbox for a validation link before logging in.</div>';
						document.getElementById('registerForm').reset();
						break;
					case 1:
						document.getElementById('errs').innerHTML += '<div class="err">Invalid name entered. (only use letters, spaces, and hyphens)</div>';
						break;
					case 2:
						document.getElementById('errs').innerHTML += '<div class="err">Invalid email entered.</div>';
						break;
					case 3:
						document.getElementById('errs').innerHTML += '<div class="err">Email does not exist. (This domain does not have a mail server)</div>';
						break;
					case 4:
						document.getElementById('errs').innerHTML += '<div class="err">Password must contain: <ul><li>At least 8 characters</li><li>At least one lower case letter</li><li>At least one upper case letter</li><li>At least one number</li><li>At least one special character (~?!@#$%^&*)</li></ul></div>';
						break;
					case 5:
						document.getElementById('errs').innerHTML += '<div class="err">Passwords do not match. Please re-enter your confirmed password.</div>';
						break;
					case 6:
						document.getElementById('errs').innerHTML += '<div class="err">Failed to add account to database. Please try again later.</div>';
						break;
					case 7:
						document.getElementById('errs').innerHTML += '<div class="err">An account with this email already exists</div>';
						break;
					case 8:
						document.getElementById('errs').innerHTML += '<div class="err">Failed to connect to the database. Please try again later.</div>';
						break;
					case 9:
						document.getElementById('errs').innerHTML += '<div class="err">Invalid CSRF Token. Please try again later.</div>';
						break;
					case 10:
						document.getElementById('errs').innerHTML += '<div class="err">Failed to send email. Please try again later.</div>';
						break;
					case 11:
						document.getElementById('errs').innerHTML += '<div class="err">Failed to insert request into database. Please try again later.</div>';
						break;
					case 12:
						document.getElementById('errs').innerHTML += '<div class="err">You have exceeded your number of allowed validation requests per day</div>';
						break;
					case 13:
						document.getElementById('errs').innerHTML += '<div class="err">The user with this email is already validated</div>';
						break;
					case 14:
						document.getElementById('errs').innerHTML += '<div class="err">A user with this email does not exist</div>';
						break;
					case 15:
						document.getElementById('errs').innerHTML += '<div class="err">Failed to connect to database. Please try again later.</div>';
						break;
					default:
						document.getElementById('errs').innerHTML += '<div class="err">An unknown error occurred. Please try again later.</div>';
				}
			}
		}
		catch(e) {
			document.getElementById('errs').innerHTML = '<div class="err">An unknown error occurred. Please try again later.</div>';
		}
		setTimeout(function() {
			document.getElementById('errs').style.transition = transition;
			document.getElementById('errs').style.opacity = 1;
		}, 10);
	});
}

// validateEmail.php
function sendValidateEmailRequest() {
	request('/SecureLogin/php/sendValidationEmail.php', '#validateEmailForm', function(data) {
		document.getElementById('errs').innerHTML = "";
		var transition = document.getElementById('errs').style.transition;
		document.getElementById('errs').style.transition = "none";
		document.getElementById('errs').style.opacity = 0;

		switch(data) {
			case '0':
				document.getElementById('errs').innerHTML += '<div>Email Sent... Check your email and click the link in the email to validate your email.</div>';
				document.getElementById('validateEmailForm').reset();
				break;
			case '1':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to send email. Please try again later.</div>';
				break;
			case '2':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to insert request into database. Please try again later.</div>';
				break;
			case '3':
				document.getElementById('errs').innerHTML += '<div class="err">You have exceeded your number of allowed validation requests per day</div>';
				break;
			case '4':
				document.getElementById('errs').innerHTML += '<div class="err">The user with this email is already validated</div>';
				break;
			case '5':
				document.getElementById('errs').innerHTML += '<div class="err">A user with this email does not exist</div>';
				break;
			case '6':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to connect to database. Please try again later.</div>';
				break;
			default:
				document.getElementById('errs').innerHTML += '<div class="err">An unknown error occurred. Please try again later.</div>';
		}
		setTimeout(function() {
			document.getElementById('errs').style.transition = transition;
			document.getElementById('errs').style.opacity = 1;
		}, 10);
	});
}

// resetPassword.php
function passwordResetRequest() {
	request('/SecureLogin/php/passwordResetRequest.php', '#resetPasswordForm', function(data) {
		document.getElementById('errs').innerHTML = "";
		var transition = document.getElementById('errs').style.transition;
		document.getElementById('errs').style.transition = "none";
		document.getElementById('errs').style.opacity = 0;

		switch(data) {
			case '0':
				document.getElementById('errs').innerHTML += '<div>An email has been sent if an account with that email exists</div>';
				document.getElementById('resetPasswordForm').reset();
				break;
			case '1':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to send email. Please try again later.</div>';
				break;
			case '2':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to insert request into database. Please try again later.</div>';
				break;
			case '3':
				document.getElementById('errs').innerHTML += '<div class="err">You have exceeded your number of allowed reset requests per day. Try again later.</div>';
				break;
			case '4':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to connect to database. Please try again later.</div>';
				break;
			case '5':
				document.getElementById('errs').innerHTML += '<div class="err">Invalid CSRF token</div>';
				break;
			case '6':
				document.getElementById('errs').innerHTML += '<div class="err">You must enter an email</div>';
				break;
			default:
				document.getElementById('errs').innerHTML += '<div class="err">An unknown error occurred. Please try again later.</div>';
		}
		setTimeout(function() {
			document.getElementById('errs').style.transition = transition;
			document.getElementById('errs').style.opacity = 1;
		}, 10);
	});
}

function changePassword() {
	request('/SecureLogin/php/changePassword.php', '#changePasswordForm', function(data) {
		document.getElementById('errs').innerHTML = "";
		var transition = document.getElementById('errs').style.transition;
		document.getElementById('errs').style.transition = "none";
		document.getElementById('errs').style.opacity = 0;
		try {
			data = JSON.parse(data);
			if(!(data instanceof Array)) {throw Exception('bad data');}

			//Show errors to user
			for(var i = 0;i < data.length;++i) {
				switch(data[i]) {
					case 0:
						document.getElementById('errs').innerHTML += '<div>Your password has been reset! You can now <a href="/SecureLogin/login">login</a></div>';
						document.getElementById('changePasswordForm').reset();
						break;
					case 1:
					case 2:
					case 7:
						document.getElementById('errs').innerHTML += '<div class="err">Invalid password reset request. If this is a mistake send a new request and click the link in the email.</div>';
						break;
					case 3:
						document.getElementById('errs').innerHTML += '<div class="err">Password must contain: <ul><li>At least 8 characters</li><li>At least one lower case letter</li><li>At least one upper case letter</li><li>At least one number</li><li>At least one special character (~?!@#$%^&*)</li></ul></div>';
						break;
					case 4:
						document.getElementById('errs').innerHTML += '<div class="err">Passwords do not match. Please re-enter your confirmed password.</div>';
						break;
					case 5:
						document.getElementById('errs').innerHTML += '<div class="err">Failed to update password in the database. Please try again later.</div>';
						break;
					case 6:
						document.getElementById('errs').innerHTML += '<div class="err">This password reset request has expired. Please send another email.</div>';
						break;
					case 8:
						document.getElementById('errs').innerHTML += '<div class="err">Failed to connect to the database. Please try again later.</div>';
						break;
					case 9:
						document.getElementById('errs').innerHTML += '<div class="err">Invalid CSRF Token. Please try again later.</div>';
						break;
					default:
						document.getElementById('errs').innerHTML += '<div class="err">An unknown error occurred. Please try again later.</div>';
				}
			}
		}
		catch(e) {
			document.getElementById('errs').innerHTML = '<div class="err">An unknown error occurred. Please try again later.</div>';
		}
		setTimeout(function() {
			document.getElementById('errs').style.transition = transition;
			document.getElementById('errs').style.opacity = 1;
		}, 10);
	});
}

// requestEvaluation.php
function requestEvaluation() {
	request('/SecureLogin/php/requestEvaluation.php', '#evaluationForm', function(data) {
		document.getElementById('errs').innerHTML = "";
		var transition = document.getElementById('errs').style.transition;
		document.getElementById('errs').style.transition = "none";
		document.getElementById('errs').style.opacity = 0;
		try {
			data = JSON.parse(data);
			if(!(data instanceof Array)) {throw Exception('bad data');}

			//Show errors to user
			for(var i = 0;i < data.length;++i) {
				switch(data[i]) {
					case 0:
						document.getElementById('errs').innerHTML += '<div>Evaluation Request Sent... We will contact you in the coming days!</div>';
						document.getElementById('evaluationForm').reset();
						break;
					case 1:
						document.getElementById('errs').innerHTML += '<div class="err">Invalid name entered. (only use letters, spaces, and hyphens)</div>';
						break;
					case 2:
						document.getElementById('errs').innerHTML += '<div class="err">Invalid description entered. (only use letters, spaces, hyphens, ".", ":", ",". and stay within 800 characters)</div>';
						break;
					case 3:
						document.getElementById('errs').innerHTML += '<div class="err">Contact method not set</div>';
						break;
					case 4:
						document.getElementById('errs').innerHTML += '<div class="err">Invalid CSRF Token. Please try again later.</div>';
						break;
					case 5:
						document.getElementById('errs').innerHTML += '<div class="err">You are not logged in.</div>';
						break;
					case 6:
						document.getElementById('errs').innerHTML += '<div class="err">Failed to connect to database. Please try again later.</div>';
						break;
					case 7:
						document.getElementById('errs').innerHTML += '<div class="err">Item is already being evaluated</div>';
						break;
					case 8:
						document.getElementById('errs').innerHTML += '<div class="err">Failed to add item to database. Please try again later.</div>';
						break;
					case 9:
						document.getElementById('errs').innerHTML += '<div class="err">Please upload a picture with any of the formats: .jpg, .gif, .png, .jpeg</div>';
						break;
					case 10:
						document.getElementById('errs').innerHTML += '<div class="err">Picture size too big. Maximum file size: 5MB: .jpg</div>';
						break;
					default:
						document.getElementById('errs').innerHTML += '<div class="err">An unknown error occurred. Please try again later.</div>';
				}
			}
		}
		catch(e) {
			document.getElementById('errs').innerHTML = '<div class="err">An unknown error occurred. Please try again later.</div>';
		}

		setTimeout(function() {
			document.getElementById('errs').style.transition = transition;
			document.getElementById('errs').style.opacity = 1;
		}, 10);
	});
}


function sendPinVerificationRequest(){
	request('/SecureLogin/php/verifyPin.php', '#verifyPinForm', function(data) {
		document.getElementById('errs').innerHTML = "";
		var transition = document.getElementById('errs').style.transition;
		document.getElementById('errs').style.transition = "none";
		document.getElementById('errs').style.opacity = 0;

		switch(data) {
			case '0':
				window.location = '/SecureLogin/';
				break;
			case '1':
				document.getElementById('errs').innerHTML += '<div class="err">Invalid CSRF Token. Please try again later.</div>';
				break;
			case '2':
				document.getElementById('errs').innerHTML += '<div class="err">Something is wrong, you do not have the permissions for this page. <a onclick="logout();">Try Logging in again</a></div>';
				break;
			case '3':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to Connect to Database. Please try again later</div>';
				break;
			case '4':
				document.getElementById('errs').innerHTML += '<div class="err">Invalid PIN Request, try again later</div>';
				break;
			case '5':
				document.getElementById('errs').innerHTML += '<div class="err">PIN Expired: <a onclick="logout();">Try Logging in again</a</div>';
				break;
			case '6':
				document.getElementById('errs').innerHTML += '<div class="err">Invalid PIN<</div>';
				break;
			default:
				document.getElementById('errs').innerHTML += data;
		}
		setTimeout(function() {
			document.getElementById('errs').style.transition = transition;
			document.getElementById('errs').style.opacity = 1;
		}, 10);
	});
}


