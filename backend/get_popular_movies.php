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
		"x-rapidapi-key: cf3356ca88msh51f5db0eefae431p19cb45jsnc81b800f8dc8"
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