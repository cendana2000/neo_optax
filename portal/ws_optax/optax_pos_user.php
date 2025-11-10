<?php

$code_store = $_GET["code_store"];

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://10.10.9.7:3131/74/pos/api/user/all/' . $code_store,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Cookie: ci_session_ppos=3u86ns8772ocu5a0l700a8pk8ghubd0m'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
header("Content-type:application/json");
print_r($response);
