<?php

// Informasi koneksi database Oracle
$host = '192.168.0.2';  // Ganti dengan nama host atau alamat IP server Oracle
$port = '1521';       // Port default untuk koneksi Oracle
$sid  = 'SISMIOP';       // SID database Oracle
$user = 'PBB';   // Nama pengguna database Oracle
$pass = 'PHSYS222';   // Kata sandi database Oracle

// Mencoba melakukan koneksi
$conn = oci_connect($user, $pass, "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port)))(CONNECT_DATA=(SID=$sid)))");

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
} else {
    echo "Koneksi berhasil!";
    oci_close($conn); // Tutup koneksi jika sudah selesai
}
