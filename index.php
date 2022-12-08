<?php 
	require_once 'php/utils.php';
	if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
		header("Location: login");
		exit;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="csrf_token" content="<?php echo createToken(); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Evaluation Page</title>
	<link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']) . '/style.css' ?>" />
</head>

<body>
	<?php require_once 'navbar.php';?>
	<?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
		<div class="formWrapper">
			<div class="evaluations">
				<h1>Evaluation Requests List</h1>
				<?php 
					require 'php/evaluationRequestsList.php';
					getEvaluationRequests(); 
				?>
			</div>
		</div>
	<?php } else { ?>
		<div class="formWrapper">
			<form id="evaluationForm" method="POST" enctype="multipart/form-data">
				<h1>Item Evaluation Page</h1>
				<div id="errs" class="errcontainer"></div>
				<div class="inputblock">
					<label for="name">Item Name</label>
					<input id="name" name="name" type="text" autocomplete="name" placeholder="Enter item name" onkeydown="if(event.key === 'Enter'){event.preventDefault();requestEvaluation();}" />
				</div>
				<div class="inputblock">
					<label for="description">Description</label>
					<textarea id="description" name="description" type="text" autocomplete="description"></textarea>
				</div>
				<div class="inputblock">
					<label for="contact">Contact</label>
					<select id="contact" name="contact" placeholder="Chose the contact method" type='text'>
						<option value="phone number">Phone Number</option>
						<option value="email">Email</option>
					</select>
				</div>
				<div class="inputblock">
					<label for="picture">Photograph</label>
					<input type="file" id="picture" name="picture">
				</div>
				<br>
				<div class="btn" onclick="requestEvaluation();">Request Evaluation</div>
			</form>
			</div>
	<?php } ?>
		<svg class="wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 32 1440 320"><defs><linearGradient id="a" x1="50%" x2="50%" y1="-10.959%" y2="100%"><stop stop-color="#ffffff" stop-opacity=".10" offset="0%"/><stop stop-color="#FFFFFF" stop-opacity=".05" offset="100%"/></linearGradient></defs><path fill="url(#a)" fill-opacity="1" d="M 0 320 L 48 288 C 96 256 192 192 288 160 C 384 128 480 128 576 112 C 672 96 768 64 864 48 C 960 32 1056 32 1152 32 C 1248 32 1344 32 1392 32 L 1440 32 L 1440 2000 L 1392 2000 C 1344 2000 1248 2000 1152 2000 C 1056 2000 960 2000 864 2000 C 768 2000 672 2000 576 2000 C 480 2000 384 2000 288 2000 C 192 2000 96 2000 48 2000 L 0 2000 Z"></path></svg>
		<svg class="wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 32 1440 320"><defs><linearGradient id="a" x1="50%" x2="50%" y1="-10.959%" y2="100%"><stop stop-color="#ffffff" stop-opacity=".10" offset="0%"/><stop stop-color="#FFFFFF" stop-opacity=".05" offset="100%"/></linearGradient></defs><path fill="url(#a)" fill-opacity="1" d="M 0 320 L 48 288 C 96 256 192 192 288 160 C 384 128 480 128 576 112 C 672 96 768 64 864 48 C 960 32 1056 32 1152 32 C 1248 32 1344 32 1392 32 L 1440 32 L 1440 2000 L 1392 2000 C 1344 2000 1248 2000 1152 2000 C 1056 2000 960 2000 864 2000 C 768 2000 672 2000 576 2000 C 480 2000 384 2000 288 2000 C 192 2000 96 2000 48 2000 L 0 2000 Z"></path></svg>
		<script src="<?php echo dirname($_SERVER['PHP_SELF']) . '/script.js' ?>"></script>
		<script src="<?php echo dirname($_SERVER['PHP_SELF']) . '/navbar.js' ?>"></script>
</body>
</html>
