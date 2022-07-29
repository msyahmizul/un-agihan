<?php
define('MyConst', TRUE);
require_once("helper.php");
//header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
//header("Access-Control-Allow-Methods : POST, GET");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["no_pekerja"]) && isset($_GET["baru"])) {
        // get komputer dipegang staff
        $komputer_baru_dipegang_staff = get_komputer_baru_dipegang_staff($_GET["no_pekerja"]);
        if ($komputer_baru_dipegang_staff) {
            $payload = array("status" => true, "komputer_data" => $komputer_baru_dipegang_staff);
        } else {
            $payload = array("status" => false, "message" => "No Existing Computer record Found For this No Siri");
        }
        http_response_code(200);
        echo json_encode($payload);
    } else if (isset($_GET["no_pekerja"])) {
        $rekod_komputer_sedia_ada_staff = get_komputer_sedia_ada_dipegang_staff($_GET["no_pekerja"]);
        if ($rekod_komputer_sedia_ada_staff) {
            $payload = array("status" => true, "komputer_data" => $rekod_komputer_sedia_ada_staff);
        } else {
            $payload = array("status" => false, "message" => "No Existing Computer record Found For this Staff");
        }
        http_response_code(200);
        echo json_encode($payload);
    } else if (isset($_GET["no_siri"]) && isset($_GET["baru"])) {
        $rekod_komputer_baru = get_komputer_baru($_GET["no_siri"]);
        if ($rekod_komputer_baru) {
            $payload = array("status" => true, "komputer_data" => $rekod_komputer_baru);
        } else {
            $payload = array("status" => false, "message" => "No Existing Computer record Found For this No Siri");
        }
        http_response_code(200);
        echo json_encode($payload);
    } else if (isset($_GET["no_siri"])) {
        $rekod_komputer_sedia_ada = get_komputer_sedia_ada($_GET["no_siri"]);
        if ($rekod_komputer_sedia_ada) {
            $payload = array("status" => true, "komputer_data" => $rekod_komputer_sedia_ada);
        } else {
            $payload = array("status" => false, "message" => "No Existing Computer record Found For this No Siri");
        }
        http_response_code(200);
        echo json_encode($payload);
    } else {
        // Bad request
        http_response_code(400);
        echo json_encode(array("status" => false, "message" => "Invalid request"));
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (check_pulang_komputer_form()) {
        // pulang komputer
        $status = pulang_komputer($_POST["no_siri"], $_POST["catatan"], $_POST["status_pulang"], $_POST["staff_username"]);
        if ($status) {
            $payload = array("status" => true, "mesage" => "Update Successfully");
        } else {
            $payload = array("status" => false, "mesage" => "Unknown Error Occur");
        }
        http_response_code(200);
        echo json_encode($payload);
        // agih komputer
    } elseif (isset($_POST['no_siri']) && isset($_POST['no_pekerja']) && isset($_POST["staff_username"])) {
        $status = agih_komputer($_POST['no_siri'], $_POST['no_pekerja'], $_POST["staff_username"]);
        if ($status) {
            $payload = array("status" => true, "mesage" => "Update Successfully");
        } else {
            $payload = array("status" => false, "mesage" => "Unknown Error Occur");
        }
        http_response_code(200);
        echo json_encode($payload);
    } elseif (isset($_POST['no_siri']) && isset($_POST['no_pekerja'])) {
        $status = update_pemilik($_POST['no_siri'], $_POST['no_pekerja']);
        if ($status) {
            $payload = array("status" => true, "mesage" => "Update Successfully");
        } else {
            $payload = array("status" => false, "mesage" => "Unknown Error Occur");
        }
        http_response_code(200);
        echo json_encode($payload);
    } else {
        // Bad request
        http_response_code(400);
        echo json_encode(array("status" => false, "message" => "Invalid request"));
    }
} else {
    // method not allowed
    http_response_code(405);
    echo json_encode(array("status" => false, "message" => "Method not allowed"));
}


























