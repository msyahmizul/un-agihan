<?php
define('MyConst', TRUE);
require_once($_SERVER["DOCUMENT_ROOT"] . "/agihan/api/helper.php");

header('Content-type: application/json');
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["jenis_alatan"]) && isset($_GET["kondisi"])) {
        $data = get_jenis_alatan();
        if ($data) {
            $payload = array("status" => true, "payload" => $data);
        } else {
            $payload = array("status" => false, "error" => "Unknown Error");
        }
        http_response_code(200);
        echo json_encode($payload);
    }

}
