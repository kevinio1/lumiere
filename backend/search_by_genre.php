<?php
/*

This endpoint retrieves movies based on a selected genre.
It sends a request to the RapidAPI advanced search endpoint
and returns the results as JSON
*/

header("Content-Type: application/json");

// Get the genre from the URL parameter
// e.g./search_by_genre.php?genre=Action
$genre = $_GET['genre'] ?? '';

// If no genre was provided, return an empty JSON response
if ($genre === '') {
    echo json_encode([]);
    exit();
}

// Initialize cURL request to call the RapidAPI endpoint
$curl = curl_init();

// Configure API request options
curl_setopt_array($curl, [
    CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/v2/search-advance?country=US&language=en-US",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",

    // Send search parameters to the API
    CURLOPT_POSTFIELDS => json_encode([
        "first" => 20,                // Number of results to return
        "after" => "",                // Used for pagination
        "includeReleaseDates" => false,

        // Sort movies by popularity (rating count)
        "sort" => [
            "sortBy" => "USER_RATING_COUNT",
            "sortOrder" => "DESC"
        ],

        // Filter movies by the selected genre
        "allGenreIds" => [$genre],

        // Only return movies (not TV shows or other media types)
        "anyTitleTypeIds" => ["movie"]
    ]),


    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "x-rapidapi-host: online-movie-database.p.rapidapi.com",
        "x-rapidapi-key: cf3356ca88msh51f5db0eefae431p19cb45jsnc81b800f8dc8"
    ],
]);

// Execute the API request
$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

// Return either an error message or the API response
if ($err) {
    echo json_encode(["error" => $err]);
} else {
    echo $response;
}
?>