<?php
define('MyConst', TRUE);
require_once("helper.php");
header('Content-type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $user_data = login_user($_POST["username"], $_POST["password"]);
        if ($user_data) {

            http_response_code(200);
            $payload = array("status" => true, "user_data" => $user_data);
        } else {
            $payload = array("status" => false, "message" => "Invalid username or password");
        }

        echo json_encode($payload);
    } else {
        http_response_code(200);
        echo json_encode(array("status" => false, "message" => "Invalid request"));
    }
} else {
    http_response_code(405);
    echo json_encode(array("status" => false, "message" => "Method not allowed"));
}

