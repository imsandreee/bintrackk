<?php
include 'config.php';

// Read incoming form data
$location_name = $_POST['location_name'] ?? '';
$gps_lat = $_POST['gps_lat'] ?? '';
$gps_lng = $_POST['gps_lng'] ?? '';
$max_depth_cm = $_POST['max_depth_cm'] ?? '';
$max_weight_kg = $_POST['max_weight_kg'] ?? '';
$device_uid = $_POST['device_uid'] ?? '';

$data = [
    "location_name" => $location_name,
    "gps_lat" => floatval($gps_lat),
    "gps_lng" => floatval($gps_lng),
    "max_depth_cm" => floatval($max_depth_cm),
    "max_weight_kg" => floatval($max_weight_kg),
    "device_id" => $device_uid
];

// Insert into Supabase `bins`
$url = $supabase_url . "/rest/v1/bins";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "apikey: $supabase_key",
    "Authorization: Bearer $supabase_key",
    "Prefer: return=minimal"
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(["error" => $error]);
    exit;
}

echo json_encode(["success" => true]);
