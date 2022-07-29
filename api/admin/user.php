<?php
define('MyConst', TRUE);
require_once($_SERVER["DOCUMENT_ROOT"] . "/agihan/api/helper.php");
header('Content-type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["list_user"])) {
        $user_data = list_user($_POST["username"]);
        if ($user_data) {
            $payload = array("status" => true, "user_data" => $user_data);
        } else {
            $payload = array("status" => false, "message" => "Only One user is active");
        }
        http_response_code(200);
        echo json_encode($payload);
    } elseif (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["signUp"])) {
        $status = signUpUser($_POST["username"], $_POST["password"], $_POST["firstName"], $_POST["lastName"]);
        if ($status) {
            $payload = array("status" => true, "message" => "Sign Up Successfully");
        } else {
            $payload = array("status" => false, "message" => "Unknown Error");
        }
        http_response_code(200);
        echo json_encode($payload);
    } else {
        http_response_code(200);
        echo json_encode(array("status" => false, "message" => "Invalid request"));
    }
} else {
    http_response_code(405);
    echo json_encode(array("status" => false, "message" => "Method not allowed"));
}

