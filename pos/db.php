<?php
$servername = "pos-ptpis-database";
$username = "root";
$password = "pos-ptpis-database";
$conn = new PDO("mysql:host=$servername;dbname=pospajak-dev", $username, $password);
$data_get = $_GET;
$db_now = 'pospajak-dev';


if (array_key_exists('toko_kode', $data_get)) {
    $toko_kode = $data_get['toko_kode'];
    $dbData = $conn->query("SELECT toko_kode, count(toko_kode) as total FROM pajak_toko WHERE toko_kode = '$toko_kode'")->fetch();
    if ($data_get['total'] > 0) {
        $db_now = 'pos_' . $dbData['toko_kode'];
    }
}

echo $db_now;
