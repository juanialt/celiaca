<?php

include '../functions.php';
include '../db_connect.php';

$params = new Params();

switch($_SERVER['REQUEST_METHOD']){

  case 'POST':{
    // User Login, Start session
    $username = $params->get("username");
    $password = $params->get("password");
    if(isset($username, $password)) {

      if(login($username, $password, $mysqli) == true) {
          // Login success
        $response = array(
          'id' => 1,
          'logged' => true
        );
        echo json_encode($response);
      } else {
        // Login failed
        $response = array(
          'id' => 1,
          'logged' => false
        );
        echo json_encode($response);
      }
    }
  }
  break;

  case 'GET':{
    // Check if the session exist
    $response = array(
      'id' => 1,
      'logged' => login_check($mysqli)
    );
    echo json_encode($response);
  }
  break;

  case 'PUT':{
    // User Login, Start session
    $username = $params->get("username");
    $password = $params->get("password");
    if(isset($username, $password)) {
      if(login($username, $password, $mysqli) == true) {
          // Login success
        $response = array(
          'id' => 1,
          'logged' => true
        );
        echo json_encode($response);
      } else {
        // Login failed
        $response = array(
          'id' => 1,
          'logged' => false
        );
        echo json_encode($response);
      }
    }
  }
  break;

  case 'DELETE':{
    // User Logout, Session destroy
    // Unset all session values
    $_SESSION = array();
    // get session parameters
    $params = session_get_cookie_params();
    // Delete the actual cookie.
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    // Destroy session
    session_destroy();

    $response = array(
      'id' => 1,
      'logged' => false,
    );
    echo json_encode($response);
  }
  break;
}


?>