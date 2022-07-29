<?php
define('MyConst', TRUE);
require_once("helper.php");
header('Content-type: application/json');
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (isset($_GET["no_pekerja"])) {
        $staff_data = get_user_no_pekerja($_GET["no_pekerja"]);
        if ($staff_data) {

            http_response_code(200);
            $payload = array("status" => true, "staff_data" => $staff_data);
        } else {
            $payload = array("status" => false);
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