<?php
// sends request to the RapidAPI movie autocomplete endpoint, returns list of films based on the users search

header("Content-Type: application/json");

$query = $_GET['q'] ?? '';

// If no search term was entered, return an empty JSON array
if ($query === '') {
    echo json_encode([]);
    exit();
}
$curl = curl_init();

// Configure API request options
curl_setopt_array($curl, [
    CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/auto-complete?q=" . urlencode($query),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "x-rapidapi-host: online-movie-database.p.rapidapi.com",
        "x-rapidapi-key: 10f110f5ecmshaec681b4b7be422p156f44jsn6f02c0336a12"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

// return either error or the api response
if ($err) {
    echo json_encode(["error" => $err]);
} else {
    echo $response;
}
?>