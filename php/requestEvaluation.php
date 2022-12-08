<?php
	require_once 'utils.php';
  $filestype = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
  $errors = [];

  if(!isset($_POST['name']) || strlen($_POST['name']) > 255 || !preg_match('/^[a-zA-Z0-9\s]+$/', $_POST['name'])) {
		$errors[] = 1;
	}

  if(!isset($_POST['description']) || strlen($_POST['description']) > 800 || !preg_match('/^[a-zA-Z0-9\s\r\n\.,:]+$/', $_POST['description'])) {
		$errors[] = 2;
	}

  if(!isset($_POST['contact'])) {
		$errors[] = 3;
	}

  if(empty($_FILES['picture']['name']) || !in_array(($_FILES["picture"]["type"]), $filestype)) {
    $errors[] = 9;
  }

  if(($_FILES['picture']['size'] / 1024) > 5000) {
    $errors[] = 10;
  } 

  if(count($errors) === 0) {
    if(isset($_POST['csrf_token']) && validateToken($_POST['csrf_token'])) {
      if(isset($_SESSION['loggedin']) && isset($_SESSION['userID']) && $_SESSION['loggedin'] === true) {

        $C = connect();
        if($C) {
          //Check if item already exists
          $res = sqlSelect($C, 'SELECT users.id, evaluations.name FROM evaluations LEFT JOIN users ON users.id = user WHERE users.id= ? AND evaluations.name= ?', 'is', $_SESSION['userID'], $_POST['name']);
          if($res && $res->num_rows === 0) {
            $imageContent = file_get_contents($_FILES['picture']['tmp_name']);

            $id = sqlInsert($C, 'INSERT INTO evaluations VALUES (NULL, ?, ?, ?, ?, ?)', 'issss', $_SESSION['userID'],  $_POST['name'], $_POST['description'], $_POST['contact'], $imageContent);
            if($id !== -1) {
              $errors[] = 0;
            }
            else {
              //Failed to insert into database
              $errors[] = 8;
            }
            $res->free_result();
          }
          else {
            $errors[] = 7;
          }
          $C->close();
        }
        else {
          $errors[] = 6;
        }
      } else {
        $errors[] = 5;
      }
    }
    else {
      $errors[] = 4;
    }
  }

  echo json_encode($errors)
?>