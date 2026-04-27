<?php
/*

This endpoint retrieves movies based on the selected genre;
sends a request to the 'RapidAPI advanced search' endpoint
and returns the results as JSON format
*/

header("Content-Type: application/json");

// Get the genre from the URL parameter e.g. search_by_genre.php?genre=Action
$genre = $_GET['genre'] ?? '';

// If no genre was provided return empty JSON response
if ($genre === '') {
    echo json_encode([]);
    exit();
}

// start cURL request to call RapidAPI endpoint
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
        "first" => 20,
        // Number of results to return
        "after" => "",
        "includeReleaseDates" => false,

        // Sort movies by popularity
        "sort" => [
            "sortBy" => "USER_RATING_COUNT",
            "sortOrder" => "DESC"
        ],

        // filter movies by the selected genre
        "allGenreIds" => [$genre],

        // only return movies -(not tv shows ect)
        "anyTitleTypeIds" => ["movie"]
    ]),


    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "x-rapidapi-host: online-movie-database.p.rapidapi.com",
        "x-rapidapi-key: 10f110f5ecmshaec681b4b7be422p156f44jsn6f02c0336a12"
    ],
]);

// execute API request
$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

// return an error message or the API response
if ($err) {
    echo json_encode(["error" => $err]);
} else {
    echo $response;
}
?>
