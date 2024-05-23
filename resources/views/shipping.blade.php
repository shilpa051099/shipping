@extends('master')

@section('content')

<?php
$apiKey = '5b866fa4da1740a28457b71084d238a6';
$secretKey = '0983c25eb4484951aef7611320a9c406';

// Base64 encode the API key and secret
$authString = base64_encode("$apiKey:$secretKey");

// Initialize cURL
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://ssapi.shipstation.com/shipments/getrates",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode(array(
    "carrierCode" => "stamps_com", // Ensure this is the correct carrier code
    "fromPostalCode" => "91355",
    "toCountry" => "US",
    "toPostalCode" => "91355",
    "weight" => array(
      "value" => 0,
      "units" => "ounces"
    ),
    "dimensions" => array(
      "units" => "inches",
      "length" => 0,
      "width" => 0,
      "height" => 0
    ),
    "confirmation" => "delivery",
    "residential" => false
  )),
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic $authString",
    "Content-Type: application/json"
  ),
));

$response = curl_exec($curl);

if (curl_errno($curl)) {
  echo 'cURL error: ' . curl_error($curl);
} else {
    $data = json_decode($response, true);


    echo "<h2>Available Shipping Services and Costs</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Service Name</th><th>Cost (USD)</th></tr>";
    foreach ($data as $rate) {
      echo "<tr><td>{$rate['serviceName']}</td><td>\${$rate['shipmentCost']}</td></tr>";
    }
    echo "</table>";
//     echo"<pre>";
//   print_r($data);
//   echo"</pre>";
}

curl_close($curl);
?>


@stop
