<?php

// Parse the requested URI
$requestUri = $_SERVER['REQUEST_URI'];
$uriSegments = explode('/', trim($requestUri, '/'));
header('Content-Type: application/json');

// The first segment after the base URL will indicate the resource (users, posts, notifications, etc.)
$resource = $uriSegments[0];
$resource2 = $uriSegments[1];

// Separate the file and parameters
$fileWithParams = $uriSegments[2];
$fileAndParams = explode('?', $fileWithParams);

// Extract the file and parameters
$file = $fileAndParams[0];
$params = isset($fileAndParams[1]) ? $fileAndParams[1] : '';

// Define the path to the specific endpoint script based on the resource
$endpointPath = __DIR__ . "/$resource2/" . $file . '.php';

// Check if the endpoint script exists
if (file_exists($endpointPath)) {
    // Include and execute the endpoint script
    require_once($endpointPath);
} else {
    // Handle the case where the requested endpoint doesn't exist
    header('HTTP/1.1 404 Not Found');
    echo json_encode(["status" => 400, "message" => "page not found"]);
}


// Rest of your code...
?>
