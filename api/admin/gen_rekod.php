<?php
define('MyConst', TRUE);
require_once($_SERVER["DOCUMENT_ROOT"] . "/agihan/api/helper.php");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["kondisi"]) && $_GET["kondisi"] == "baru") {
        gen_report_alatan_baru();
    }

    if (isset($_GET["kondisi"]) && $_GET["kondisi"] == "lama") {
        gen_report_alatan_lama();
    }

}