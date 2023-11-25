<?php

// Define endpoint for example
$endpoint = $_GET['endpoint'] ?? '';

// Process the request based on the endpoint
switch ($endpoint) {
    case 'hello':
        $response = array('message' => 'Hello, API!');
        break;

    default:
        $response = array('error' => 'Invalid endpoint');
        break;
}

// Set headers to indicate JSON content type
header('Content-Type: application/json');

// Output the JSON response
echo json_encode($response);
