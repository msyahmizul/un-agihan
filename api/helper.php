<?php
if (!defined('MyConst')) {
    die('Direct access not permitted');
}
session_start();
$servername = "localhost";
$username = "agihan";
$password = "AgihanCict";
//$password = "AgihanCict";
$conn = new mysqli($servername, $username, $password, null, 1998);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function get_user_no_pekerja($no_pekerja)
{
    global $conn;
    $sql = "SELECT a.nama AS NAME, a.no_pekerja as NO_PEKERJA,a.KOD_GRED_JAWATAN
FROM audit.senarai_staff a WHERE a.no_pekerja = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $no_pekerja);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;

    } else {
        return false;
    }


}

function get_komputer_sedia_ada_dipegang_staff($no_pekerja)
{
    global $conn;
    $sql = "SELECT c.NO_SIRI,
       a.STATUS_PEMEGANG,
       f.NAME_PARAMETER                                  AS JENIS_ALATAN,
       h.NAME_PARAMETER                                  AS JENAMA,
       g.NAME_PARAMETER                                  AS MODEL,
       DATE_FORMAT(i.TARIKH_PULANG_KOMPUTER, '%d/%m/%Y') AS TARIKH_PULANG_KOMPUTER
FROM agihan.rekod_komputer a
       INNER JOIN audit.senarai_staff b ON b.no_pekerja = ?
       INNER JOIN agihan.komputer_data c ON c.KOMPUTER_PK = a.NO_SIRI_FK
       LEFT JOIN agihan.rekod_mengagih_komputer d ON d.REKOD_KOMPUTER_BARU_FK = c.KOMPUTER_PK
       INNER JOIN agihan.spec_komputer e ON e.SPEC_PK = c.SPEC_FK
       INNER JOIN agihan.parameter f ON f.PARAM_PK = c.JENIS_ALATAN
       INNER JOIN agihan.parameter g ON g.PARAM_PK = e.MODEL
       INNER JOIN agihan.parameter h ON h.PARAM_PK = c.JENAMA
       LEFT JOIN agihan.rekod_memulang_komputer i ON i.REKOD_KOMPUTER_SEDIA_ADA_FK = c.KOMPUTER_PK
WHERE a.STAFF_FK = b.STAFF_PK
  AND d.TARIKH_AMBIL is NULL;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $no_pekerja);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_all(MYSQLI_ASSOC);
        return $row;

    } else {
        return false;
    }
}

function get_komputer_sedia_ada($no_siri)
{
    global $conn;
    $sql = "SELECT b.NO_SIRI,
       a.STATUS_PEMEGANG,
       c.NAME_PARAMETER AS JENIS_ALATAN,
       e.NAME_PARAMETER AS JENAMA,
       g.NAME_PARAMETER AS MODEL,
       h.no_pekerja,
       h.nama,
       h.KOD_GRED_JAWATAN
FROM agihan.rekod_komputer a
       INNER JOIN agihan.komputer_data b ON b.NO_SIRI = ?
       INNER JOIN agihan.komputer_data d ON d.KOMPUTER_PK = a.NO_SIRI_FK
       INNER JOIN agihan.parameter c ON c.PARAM_PK = d.JENIS_ALATAN
       INNER JOIN agihan.parameter e ON e.PARAM_PK = d.JENAMA
       INNER JOIN agihan.spec_komputer f ON f.SPEC_PK = d.SPEC_FK
       INNER JOIN agihan.parameter g ON g.PARAM_PK = f.MODEL
       LEFT JOIN audit.senarai_staff h ON h.STAFF_PK = a.STAFF_FK
       LEFT JOIN agihan.rekod_mengagih_komputer i ON i.REKOD_KOMPUTER_BARU_FK = a.NO_SIRI_FK

WHERE a.NO_SIRI_FK = b.KOMPUTER_PK AND i.TARIKH_AMBIL is NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $no_siri);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;

    } else {
        return false;
    }


}

function get_komputer_baru($no_siri)
{
    global $conn;
    $sql = "SELECT a.NO_SIRI,
       a.NO_DAFTAR_HARTA,
       b.RAM_GB,
       b.PROCESSOR,
       b.STORAGE,
       c.NAME_PARAMETER AS OS,
       d.NAME_PARAMETER AS MODEL,
       e.NAME_PARAMETER AS JENAMA,
       f.NAME_PARAMETER AS JENIS_ALATAN,
  b.HARGA_PEROLEHAN
FROM agihan.komputer_data a
       INNER JOIN agihan.spec_komputer b ON b.SPEC_PK = a.SPEC_FK
       LEFT JOIN agihan.parameter c ON c.PARAM_PK = b.OS
       INNER JOIN agihan.parameter d ON d.PARAM_PK = b.MODEL
       INNER JOIN agihan.parameter e ON e.PARAM_PK = a.JENAMA
       INNER JOIN agihan.parameter f ON f.PARAM_PK = a.JENIS_ALATAN
WHERE a.NO_SIRI = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $no_siri);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;

    } else {
        return false;
    }

}

function get_komputer_baru_dipegang_staff($no_pekerja)
{
    global $conn;
    $sql = "SELECT b.NO_SIRI,
       d.NAME_PARAMETER                        AS JENIS_ALATAN,
       e.NAME_PARAMETER                        AS JENAMA,
       f.NAME_PARAMETER                        AS MODEL,
       DATE_FORMAT(h.TARIKH_AMBIL, '%d/%m/%Y') AS TARIKH_AMBIL
FROM agihan.rekod_komputer a
       INNER JOIN agihan.komputer_data b ON b.KOMPUTER_PK = a.NO_SIRI_FK
       INNER JOIN audit.senarai_staff c ON c.no_pekerja = ?
       INNER JOIN agihan.parameter d ON d.PARAM_PK = b.JENIS_ALATAN
       INNER JOIN agihan.parameter e ON e.PARAM_PK = b.JENAMA
       INNER JOIN agihan.spec_komputer g ON g.SPEC_PK = b.SPEC_FK
       INNER JOIN agihan.parameter f ON f.PARAM_PK = g.MODEL
       INNER JOIN agihan.rekod_mengagih_komputer h ON h.REKOD_KOMPUTER_BARU_FK = a.NO_SIRI_FK
WHERE a.STAFF_FK = c.STAFF_PK";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $no_pekerja);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_all(MYSQLI_ASSOC);
        return $row;

    } else {
        return false;
    }
}

function check_pulang_komputer_form()
{
    if (isset($_POST["no_siri"]) && isset($_POST["catatan"]) && isset($_POST["status_pulang"]) && isset($_POST["staff_username"])) {
        return true;
    } else {
        return false;
    }
}


function pulang_komputer($no_siri, $catatan, $status_pulang, $STAFF_PENGESAHAN)
{
    global $conn;
    $sql = "INSERT INTO agihan.rekod_memulang_komputer (REKOD_KOMPUTER_SEDIA_ADA_FK, CATATAN, STAFF_PENGEHSAHAN)
SELECT a.KOMPUTER_PK, ?, b.USER_PK
FROM agihan.komputer_data a
       INNER JOIN agihan.user b ON b.USERNAME = ?
WHERE a.NO_SIRI = ?";

    $sql2 = "UPDATE
  agihan.rekod_komputer a
    INNER JOIN agihan.komputer_data b ON b.NO_SIRI = ?
SET a.STATUS_PEMEGANG = ?
WHERE a.NO_SIRI_FK = b.KOMPUTER_PK";
    $stmt = $conn->prepare($sql);
    $stmt2 = $conn->prepare($sql2);
    $stmt->bind_param("sss", $catatan, $STAFF_PENGESAHAN, $no_siri);
    $stmt2->bind_param("si", $no_siri, $status_pulang);
    if ($stmt->execute() && $stmt2->execute()) {
        return true;
    } else {
        return false;
    }
}

function agih_komputer($no_siri, $no_pekerja, $STAFF_PENGESAHAN)
{
//    echo $no_siri . " " . $no_pekerja . " " . $STAFF_PENGESAHAN;
    global $conn;
    $sql = "INSERT INTO agihan.rekod_mengagih_komputer (REKOD_KOMPUTER_BARU_FK, STAFF_PENGESAHAN)
SELECT a.KOMPUTER_PK, b.USER_PK
FROM agihan.komputer_data a
       INNER JOIN agihan.user b ON b.USERNAME = ?
WHERE a.NO_SIRI = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $STAFF_PENGESAHAN, $no_siri);

    if (insert_rekod_komputer($no_siri, $no_pekerja, 6) && $stmt->execute()) {
        $rekod_id = $conn->insert_id;
        if (gen_no_rujukan($rekod_id, $no_siri)) {
            return true;
        }
        return false;
    } else {
        return false;
    }

}

function insert_rekod_komputer($no_siri, $no_pekerja, $status_komputer)
{
    global $conn;
    $sql = "INSERT INTO agihan.rekod_komputer (NO_SIRI_FK, STAFF_FK, STATUS_PEMEGANG)
SELECT a.KOMPUTER_PK, b.STAFF_PK, ?
FROM agihan.komputer_data a
       INNER JOIN audit.senarai_staff b ON b.no_pekerja = ?
WHERE a.NO_SIRI = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $status_komputer, $no_pekerja, $no_siri);

    if ($stmt->execute()) {
        return true;
    }
    return false;

}


function gen_no_rujukan($rekod_id, $no_siri)
{
    global $conn;

    // get jenis alatan
    $sql = "SELECT b.KOD_PARAMETER AS KOD_ALATAN
FROM agihan.komputer_data a
       INNER JOIN agihan.parameter b ON b.PARAM_PK = a.JENIS_ALATAN
WHERE a.NO_SIRI = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $no_siri);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();
    $KOD_ALATAN = $row["KOD_ALATAN"];

    $NO_RUJUKAN = date("Y") . "/" . $KOD_ALATAN . "/" . sprintf('%04d', $rekod_id);

    $sql = "UPDATE agihan.rekod_mengagih_komputer a SET a.NO_RUJUKAN_AGIHAN = ? WHERE a.REKOD_PK = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $NO_RUJUKAN, $rekod_id);
    $stmt->execute();

    if ($stmt->execute()) {
        return true;

    } else {
        return false;
    }

}


function update_no_daftar_harta($no_siri, $no_daftar_harta)
{
    global $conn;
    $sql = "UPDATE agihan.komputer_data a
SET a.NO_DAFTAR_HARTA = ? WHERE a.NO_SIRI = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $no_daftar_harta, $no_siri);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function print_data($no_siri)
{
    global $conn;
    $sql = "SELECT c.NAME_PARAMETER                        AS JENIS_ALATAN,
       b.NO_SIRI,
       b.NO_DAFTAR_HARTA,
       d.NAME_PARAMETER                        AS JENAMA,
       f.NAME_PARAMETER                        AS MODEL,
       e.HARGA_PEROLEHAN,
       g.nama,
       g.no_pekerja,
       g.FAKULTI                               AS KOD_PTJ,
       h.NO_RUJUKAN_AGIHAN,
       DATE_FORMAT(h.TARIKH_AMBIL, '%d/%m/%Y') AS TARIKH_AMBIL,
       b.CATATAN,
      b.JENIS_ALATAN AS KOD_JENIS_ALATAN

FROM agihan.rekod_komputer a
       INNER JOIN agihan.komputer_data b ON b.NO_SIRI = ?
       INNER JOIN agihan.parameter c ON c.PARAM_PK = b.JENIS_ALATAN
       INNER JOIN agihan.parameter d ON d.PARAM_PK = b.JENAMA
       INNER JOIN agihan.spec_komputer e ON e.SPEC_PK = b.SPEC_FK
       INNER JOIN agihan.parameter f ON f.PARAM_PK = e.MODEL
       INNER JOIN audit.senarai_staff g ON g.STAFF_PK = a.STAFF_FK
       INNER JOIN agihan.rekod_mengagih_komputer h ON h.REKOD_KOMPUTER_BARU_FK = b.KOMPUTER_PK
WHERE a.NO_SIRI_FK = b.KOMPUTER_PK";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $no_siri);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;

    } else {
        return false;
    }

}

function get_last_pulang_alatan($no_pekerja)
{
//    {model} -  {service tag}
    global $conn;
    $sql = "SELECT d.NO_SIRI, f.NAME_PARAMETER AS MODEL
FROM agihan.rekod_komputer a
       INNER JOIN audit.senarai_staff b ON b.no_pekerja = ?
       LEFT JOIN agihan.rekod_mengagih_komputer c ON c.REKOD_KOMPUTER_BARU_FK = a.NO_SIRI_FK
       INNER JOIN agihan.komputer_data d ON d.KOMPUTER_PK = a.NO_SIRI_FK
       INNER JOIN agihan.spec_komputer e ON e.SPEC_PK = d.SPEC_FK
       INNER JOIN agihan.parameter f ON f.PARAM_PK = e.MODEL

WHERE a.STAFF_FK = b.STAFF_PK
  AND c.REKOD_PK IS NULL;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $no_pekerja);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;

    } else {
        return false;
    }


}

function update_pemilik($no_siri, $no_pekerja)
{
    global $conn;
    $sql = "UPDATE agihan.rekod_komputer a
  LEFT JOIN audit.senarai_staff b ON b.no_pekerja = ?
  LEFT JOIN agihan.komputer_data c ON c.NO_SIRI = ?
SET a.STAFF_FK = b.STAFF_PK
WHERE a.NO_SIRI_FK = c.KOMPUTER_PK";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ss", $no_pekerja, $no_siri);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }

}

function login_user($username, $password)
{
    if (!check_username_password($username, $password)) {
        return false;
    }

    global $conn;
    $sql = "SELECT a.USERNAME, a.ROLE
FROM agihan.user a
WHERE a.USERNAME = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
//        $token = bin2hex(random_bytes(64));
        $token = bin2hex(openssl_random_pseudo_bytes(64));
        $row = $result->fetch_assoc();
        add_token_user($row["USERNAME"], $token);
        $row["AUTH_TOKEN"] = $token;
        return $row;

    } else {
        return false;
    }
}

function get_user_detail()
{

}

function check_username_password($username, $password)
{
    global $conn;
    $sql = "SELECT a.PASSWORD
FROM agihan.user a
WHERE a.USERNAME = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["PASSWORD"])) {
            return true;
        } else {
            return false;
        }

    } else {
        return false;
    }
}

function add_token_user($username, $token)
{
    global $conn;
    $sql = "UPDATE agihan.user a
SET a.AUTH_TOKEN    = ?,a.LAST_LOGIN = CURRENT_TIMESTAMP
WHERE a.USERNAME = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $username);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function check_session_token($token, $username)
{
    global $conn;
    $sql = "SELECT a.AUTH_TOKEN FROM agihan.user a WHERE a.AUTH_TOKEN = ? AND a.USERNAME = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $username);

    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function list_user($username)
{
    global $conn;
    $sql = "SELECT a.USERNAME,a.FIRST_NAME,a.LAST_NAME,a.LAST_LOGIN,a.ROLE
FROM agihan.user a WHERE a.USERNAME !=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_all(MYSQLI_ASSOC);
        return $row;

    } else {
        return false;
    }
}

function signUpUser($username, $password, $firstName, $lastName)
{
    global $conn;

    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO agihan.user (USERNAME, PASSWORD, FIRST_NAME, LAST_NAME, ROLE) VALUE (?,?,?,?,2)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $pass_hash, $firstName, $lastName);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function get_jenis_alatan()
{
    global $conn;
    $sql = "SELECT a.NAME_PARAMETER, a.PARAM_PK
FROM agihan.parameter a
WHERE a.KUMPULAN_FK = 2";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_all(MYSQLI_ASSOC);
        return $row;
    } else {
        return false;
    }
}

function gen_report_alatan_baru()
{
    global $conn;
    $delimiter = ",";
    $filename = "Senarai Notebook " . date('Y-m-d') . " - Baru.csv";
    $sql = "SELECT a.NO_SIRI,
       a.NO_DAFTAR_HARTA,
       d.no_pekerja,
       d.nama,
       d.FAKULTI,
       DATE_FORMAT(c.TARIKH_AMBIL, '%d/%m/%Y') AS TARIKH_AMBIL,
       e.NAME_PARAMETER                        AS JENIS_ALATAN,
        a.JENIS_ALATAN as KOD_JENIS_ALATAN,
       a.CATATAN
FROM agihan.komputer_data a
       LEFT JOIN agihan.rekod_komputer b ON b.NO_SIRI_FK = a.KOMPUTER_PK
       LEFT JOIN agihan.rekod_mengagih_komputer c ON c.REKOD_KOMPUTER_BARU_FK = a.KOMPUTER_PK
       LEFT JOIN audit.senarai_staff d ON d.STAFF_PK = b.STAFF_FK
       LEFT JOIN agihan.parameter e ON e.PARAM_PK = a.JENIS_ALATAN
WHERE a.IS_NEW IS TRUE
ORDER BY e.NAME_PARAMETER, d.nama ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $f = fopen('php://memory', 'w');
        $fields = array('No Siri', 'No Daftar Harta', 'No Pekerja', 'Nama', 'FAKULTI', 'Tarikh Ambil', "Jenis Alatan");
        fputcsv($f, $fields, $delimiter);
        while ($row = $result->fetch_assoc()) {
            if ($row["KOD_JENIS_ALATAN"] == 15 || $row["KOD_JENIS_ALATAN"] == 16) {
                $lineData = array($row["NO_SIRI"], $row["NO_DAFTAR_HARTA"], $row["no_pekerja"], $row["nama"], $row["FAKULTI"], $row["TARIKH_AMBIL"], $row["JENIS_ALATAN"],str_replace("No. Siri Monitor : ","",$row["CATATAN"]));
            } else {

                $lineData = array($row["NO_SIRI"], $row["NO_DAFTAR_HARTA"], $row["no_pekerja"], $row["nama"], $row["FAKULTI"], $row["TARIKH_AMBIL"], $row["JENIS_ALATAN"]);
            }
            fputcsv($f, $lineData, $delimiter);

        }
        fseek($f, 0);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        header('filename: ' . $filename . '');
        fpassthru($f);
    }


}

function gen_report_alatan_lama()
{
    global $conn;
    $delimiter = ",";
    $filename = "Senarai Notebook " . date('Y-m-d') . " - Lama .csv";
    $sql = "SELECT a.NO_SIRI,
       a.NO_DAFTAR_HARTA,
       c.no_pekerja,
       c.nama,
       c.FAKULTI,
       e.NAME_PARAMETER                                  AS STATUS_PULANG,
       d.CATATAN,
       DATE_FORMAT(d.TARIKH_PULANG_KOMPUTER, '%d/%m/%Y') AS TARIKH_PULANG_KOMPUTER

FROM agihan.komputer_data a
       INNER JOIN agihan.rekod_komputer b ON b.NO_SIRI_FK = a.KOMPUTER_PK
       LEFT JOIN audit.senarai_staff c ON c.STAFF_PK = b.STAFF_FK
       LEFT JOIN agihan.rekod_memulang_komputer d ON d.REKOD_KOMPUTER_SEDIA_ADA_FK = a.KOMPUTER_PK
       INNER JOIN agihan.parameter e ON e.PARAM_PK = b.STATUS_PEMEGANG
WHERE a.IS_NEW IS FALSE ORDER BY e.NAME_PARAMETER DESC ,c.nama ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $f = fopen('php://memory', 'w');
        $fields = array('No Siri', 'No Daftar Harta', 'No Pekerja', 'Nama', 'FAKULTI', 'Status Pulang', "Catatan", "Tarikh Pulang Komputer",);
        fputcsv($f, $fields, $delimiter);
        while ($row = $result->fetch_assoc()) {
            $lineData = array($row["NO_SIRI"], $row["NO_DAFTAR_HARTA"], $row["no_pekerja"], $row["nama"], $row["FAKULTI"], $row["STATUS_PULANG"], $row["CATATAN"], $row["TARIKH_PULANG_KOMPUTER"]);
            fputcsv($f, $lineData, $delimiter);

        }
        fseek($f, 0);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        header('filename: ' . $filename . '');
        fpassthru($f);
    }


}