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

//user-phone must be included in every response
$user_phone = $jsonData->user_phone ?? ADMIN_PHONE;
$user = new User($user_phone);
$locations = json_decode(file_get_contents('locations.json'));

// Process the request based on the endpoint
$segment = $segments[0];
switch ($segment) {
    case 'create-garden':
        $garden = $jsonData->garden;
        $garden = $user->add_garden($garden);
        $response = $garden;
        break;
    case 'get-gardens':
        $gardens = $user->get_gardens();
        $response = $gardens;
        break;
    case 'get-garden':
        $id = $segments[1];
        $garden = $user->get_garden($id);
        $response = $garden;
        break;
    case 'generate':
        foreach ($locations as $area) {
            $garden = $jsonData->garden;

            $garden->location = array("Marg'ilon", "Farg'ona", "Qo'shtepa", "Qo'shtepa", "Qo'shtepa");
            $garden->user_phone = array('+99890782248', '+998901234567', '+998936587451');
            $garden->status = array('published', 'verified');
            $garden->description = array('pishgan, mazzali', '1 haftada pishadi', 'lorem bor mashetta', 'judayam oz qoldi, atiga 4 kilo', '1 oyda pishadi');
            $garden->price_for_kg = array("100ming so'm", "10000 so'm");
            $garden->filters = array(1, 2, 3, 4,);
            $title = [
                'olma' => ['qirmizi', 'semerenka', 'Feruza'],
                'banan' => ['kavendish', 'Lady Finger', 'qizil'],
                'mandarin' => ['afourer', 'nadorkott'],
                'uzum' => ['husayni', 'hasayni', 'chillaki', 'kishmish'],
                'olma ' => ['qirmizi', 'semerenka', 'Feruza'],
                "o'rik" => ['isfarak', 'subhona', "oq o'rik"],
                'anor' => ['tuyatish', 'oq dona', 'achiq don'],
                'gilos' => ['qora gilos', 'bahor', 'sarvi-surhoni']
            ];
            $garden->title = trim(array_rand($title));
            $garden->sort = $title[$garden->title];

            $garden = $user->generate_garden($garden, $area);
        }
        $response = $user->get_gardens();
        break;
    default:
        $response = array('error' => 'Invalid endpoint');
        break;
}

// Set headers to indicate JSON content type
header('Content-Type: application/json');

// Output the JSON response
echo json_encode($response);
