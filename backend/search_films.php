<?php
// This file sends a request to the RapidAPI movie autocomplete endpoint
// It returns a list of films based on the user's search query

header("Content-Type: application/json");

$query = $_GET['q'] ?? '';

// If no search term was entered, return an empty JSON array
if ($query === '') {
    echo json_encode([]);
    exit();
}

// Initialize cURL request
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
        "x-rapidapi-key: cf3356ca88msh51f5db0eefae431p19cb45jsnc81b800f8dc8"
    ],
]);

// Execute API request
$response = curl_exec($curl);
$err = curl_error($curl);

// Close cURL connection
curl_close($curl);

// Return either an error or the API response
if ($err) {
    echo json_encode(["error" => $err]);
} else {
    echo $response;
}
?>