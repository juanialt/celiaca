<?php
define('ENCRYPTION', 'sha512'); // The encryption algorithm

function sec_session_start() {
    $session_name = 'sec_session_id'; // Set a custom session name
    $secure = false; // Set to true if using https.
    $httponly = true; // This stops javascript being able to access the session id.
    ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies.
    $cookieParams = session_get_cookie_params(); // Gets current cookies params.
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    session_name($session_name); // Sets the session name to the one set above.
    session_start(); // Start the php session
    session_regenerate_id(); // regenerated the session, delete the old one.
}

function login($email, $password, $mysqli) {
    // Using prepared Statements means that SQL injection is not possible.
    if ($stmt = $mysqli->prepare("SELECT id, email, password FROM user WHERE email = ? LIMIT 1")) {
        $stmt->bind_param('s', $email); // Bind "$email" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();
        $stmt->bind_result($user_id, $email, $db_password); // get variables from result.
        $stmt->fetch();
        if ($stmt->num_rows == 1) { // If the user exists
            $password = hash(ENCRYPTION, $password); // hash the password
            if ($db_password == $password) { // Check if the password in the database matches the password the user submitted.
                // Password is correct!
                $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
                $user_id = preg_replace("/[^0-9]+/", "", $user_id); // XSS protection as we might print this value
                $email = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $email); // XSS protection as we might print this value
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $email;
                $_SESSION['login_string'] = hash(ENCRYPTION, $password . $user_browser);
                // Login successful.
                return true;
            }
            else {
                // Password is not correct
                return false;
            }
        }
        else {
            // No user exists.
            return false;
        }
    }
}

function login_check($mysqli) {
    // Check if all session variables are set
    if (isset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['login_string'])) {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['user_name'];
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

        if ($stmt = $mysqli->prepare("SELECT password FROM user WHERE id = ? LIMIT 1")) {
            $stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
            $stmt->execute(); // Execute the prepared query.
            $stmt->store_result();
            if ($stmt->num_rows == 1) { // If the user exists
                $stmt->bind_result($password); // get variables from result.
                $stmt->fetch();
                $login_check = hash(ENCRYPTION, $password . $user_browser);
                if ($login_check == $login_string) {
                    // Logged In!!!!
                    return true;
                }
                else {
                    // Not logged in
                    return false;
                }
            }
            else {
                // Not logged in
                return false;
            }
        }
        else {
            // Not logged in
            return false;
        }
    }
    else {
        // Not logged in
        return false;
    }
}

/* Parse from dd/mm/yyyy to yyyy-mm-dd */
function parseMySqlDate($date){
    if(isset($date) && $date != ''){
        $mysqlDate = str_replace('/', '-', $date);
        $mysqlDate = strtotime($mysqlDate);
        $mysqlDate = date('Y-m-d', $mysqlDate);

        return $mysqlDate;
    }
    return NULL;
}

/* Parse from dd/mm/yyyy hh:mm:ss to yyyy-mm-dd hh:mm:ss */
function parseMySqlDateTime($date){
    if(isset($date) && $date != ''){
        $mysqlDate = str_replace('/', '-', $date);
        $mysqlDate = strtotime($mysqlDate);
        $mysqlDate = date('Y-m-d H:i:s', $mysqlDate);

        return $mysqlDate;
    }
    return NULL;
}

/* Parse from yyyy-mm-dd to dd/mm/yyyy */
function parseJavascriptDate($date){
    if(isset($date) && $date != NULL){
        return date('d/m/Y', strtotime($date));
    }
    return '';
}

/* Parse from yyyy-mm-dd to dd/mm/yyyy */
function parseJavascriptDateTime($date){
    if(isset($date) && $date != NULL){
        return date('d/m/Y H:i', strtotime($date));
    }
    return '';
}



class Params {
    private $params = Array();

    public function __construct() {
        $this->_parseParams();
    }

    /**
     * @brief Lookup request params
     * @param string $name Name of the argument to lookup
     * @param mixed $default Default value to return if argument is missing
     * @returns The value from the GET/POST/PUT/DELETE value, or $default if not set
     */
    public function get($name, $default = null) {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        }
        else {
            return $default;
        }
    }

    public function set($name, $value, $default = null) {
        if (isset($this->params[$name])) {
            $this->params[$name] = $value;
        }
        else {
            return $default;
        }
    }

    private function _parseParams() {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "PUT") {
            if ($input = file_get_contents("php://input")) {
                if ($json_post = json_decode($input, true)) {
                    $this->params = $json_post;
                }
                else {
                    parse_str($input, $variables);
                    $this->params = $variables;
                }
            }
            $GLOBALS["_{$method}"] = $this->params;
            // Add these request vars into _REQUEST, mimicing default behavior, PUT/DELETE will override existing COOKIE/GET vars
            $_REQUEST = $this->params + $_REQUEST;
        }
        else if ($method == "DELETE") {
            $this->params = $_GET;
        }
        else if ($method == "GET") {
            $this->params = $_GET;
        }
        else if ($method == "POST") {
            if ($input = file_get_contents("php://input")) {
                if ($json_post = json_decode($input, true)) {
                    $this->params = $json_post;
                }
                else {
                    parse_str($input, $variables);
                    $this->params = $variables;
                }
            }
        }
    }
}

?>