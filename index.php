<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'loader.php';


use app\DB;
use app\User;

DB::connect();

// Define endpoint for example
$endpoint = $_GET['user'] ?? '';
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', trim($path, '/'));

$jsonData = json_decode(file_get_contents('php://input'));
dd($jsonData->garden);

// Process the request based on the endpoint
foreach($segments as $segment){
    switch ($segment) {
        case 'create-garden':
            $user_phone = $jsonData->user_phone;

            $user = new User($user_phone);
            $garden = $jsonData->garden;
            $garden = $user->add_garden($garden);

            $response = array('message' => 'Hello, API!');
            break;
        case '':

    
        default:
            $response = array('error' => 'Invalid endpoint');
            break;
    }
}


// Set headers to indicate JSON content type
header('Content-Type: application/json');

// Output the JSON response
echo json_encode($response);
