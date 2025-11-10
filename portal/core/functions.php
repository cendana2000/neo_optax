<?php
function base_url($path = '')
{
    // Tentukan protokol: http atau https
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

    // Dapatkan host (misalnya: localhost atau domain.com)
    $host = $_SERVER['HTTP_HOST'];

    // Dapatkan path ke folder aplikasi (jika ada)
    $scriptName = $_SERVER['SCRIPT_NAME']; // contoh: /myapp/index.php
    $scriptDir = rtrim(dirname($scriptName), '/\\'); // hasil: /myapp

    // Gabungkan semua
    $base = $protocol . $host . $scriptDir . '/';

    // Tambahkan path tambahan jika diberikan
    return $base . ltrim($path, '/');
}
