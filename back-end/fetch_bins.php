<?php
require_once 'config.php';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, SUPABASE_URL.'/rest/v1/bins');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: '.SUPABASE_ANON_KEY,
    'Authorization: Bearer '.SUPABASE_ANON_KEY,
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

header('Content-Type: application/json');
echo $response;
