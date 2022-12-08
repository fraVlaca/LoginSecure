<?php
	require_once 'utils.php';
	 
  function getEvaluationRequests(){
    $C = connect();
    if($C) {
      //retrieve evaluation items
      $res = sqlSelect($C, 'SELECT users.id, users.name, evaluations.name AS itemName, evaluations.description, evaluations.picture, evaluations.contact, users.email, users.phoneNumber FROM evaluations LEFT JOIN users ON users.id = evaluations.user');
      if($res && $res->num_rows > 0) {
        while($row = $res->fetch_assoc()) {
          if ($row['contact'] == 'email') {
            $contact = $row['email'];
          } else {
            $contact = $row['phoneNumber'];
          }
          echo 
            '<div class="valuationBlock">
              <h2>' . htmlspecialchars($row['itemName'], ENT_QUOTES) . ' by ' . htmlspecialchars($row['name'], ENT_QUOTES) . '</h2>
              <img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['picture']) . '"/>
              <p><b>Description: </b>' . htmlspecialchars($row['description'], ENT_QUOTES) . '</p>
              <p><b>Preferred contact:</b> ' . htmlspecialchars($contact, ENT_QUOTES) . '</p>
              <p><b>User Id:</b> ' . htmlspecialchars($row['id'], ENT_QUOTES) . '</p>
            </div>';
        }
      }
      else {
        echo '<div class="err">No Evaluation request was found</div>';
      }
      $C->close();
    }
    else {
      echo'<div class="err">Connectin with Database failed</div>';
    }
  }