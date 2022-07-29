<?php
define('MyConst', TRUE);
require_once("./api/helper.php");
require_once('tbs_class.php');
require_once('tbs_plugin_opentbs.php');

$TBS = new clsTinyButStrong;
$TBS->PlugIn(TBS_INSTALL, OPENTBS_PLUGIN);
$resit_data = print_data($_GET["no_siri"]);
$last_pulang_komputer = get_last_pulang_alatan($resit_data['no_pekerja']);

$jenisAlatan = strtoupper($resit_data["JENIS_ALATAN"]);
$noSiri = strtoupper($resit_data["NO_SIRI"]);
$noDaftarHarta = strtoupper($resit_data["NO_DAFTAR_HARTA"]);

$JenamaAlat = $resit_data["JENAMA"];
$ModelAlat = $resit_data["MODEL"];
$HargaAlat = $resit_data["HARGA_PEROLEHAN"];

$nama_staff = $resit_data['nama'];
$no_pekerja = $resit_data['no_pekerja'];
$kodPTJ = $resit_data['KOD_PTJ'];

$tel_pej = "075532136";
$tel_hp = "";

$tarikh = $resit_data["TARIKH_AMBIL"];
$noRujukan = $resit_data['NO_RUJUKAN_AGIHAN'];

//    {model} -  {service tag}


// Catatan

if ($resit_data["KOD_JENIS_ALATAN"] == 15 || $resit_data["KOD_JENIS_ALATAN"] == 16) {
    $lastPulangAlatan = $resit_data["CATATAN"];
} elseif ($last_pulang_komputer) {
    $lastPulangAlatan = $last_pulang_komputer['MODEL'] . '  -  ' . $last_pulang_komputer['NO_SIRI'];
} else {
    $lastPulangAlatan = '     -     ';
}


$TBS->LoadTemplate('agih.docx', OPENTBS_ALREADY_UTF8);
//$TBS->Show();
$fileName = $no_pekerja . "-" . trim($nama_staff) . ".docx";
$TBS->Show(OPENTBS_DOWNLOAD, $fileName);
