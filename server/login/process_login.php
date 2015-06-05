<?php

include 'functions.php';
include 'db_connect.php';


if (count($_POST) == 0){
   $username = isset($_GET['username']) ? $_GET['username'] : NULL;
   $password = isset($_GET['password']) ? $_GET["password"] : NULL;
} else {
   $username = isset($_POST['username']) ? $_POST['username'] : NULL;
   $password = isset($_POST['password']) ? $_POST["password"] : NULL;
}

if(isset($username, $password)) {
   if(login($username, $password, $mysqli) == true) {
      // Login success
      $response = array(
         'logged' => true,
      );
      echo json_encode($response);
   } else {
      // Login failed
      $response = array('logged' => false);
      echo json_encode($response);
   }
}

?>