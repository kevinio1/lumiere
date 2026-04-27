<?php

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/title/get-most-popular-movies?currentCountry=US&purchaseCountry=US&homeCountry=US",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"Content-Type: application/json",
		"x-rapidapi-host: online-movie-database.p.rapidapi.com",
		"x-rapidapi-key: 10f110f5ecmshaec681b4b7be422p156f44jsn6f02c0336a12"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}