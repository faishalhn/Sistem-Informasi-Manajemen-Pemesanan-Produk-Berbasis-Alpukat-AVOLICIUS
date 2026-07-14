<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| AVOLICIUS
| CORS Configuration
|--------------------------------------------------------------------------
*/

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

    http_response_code(200);

    exit;

}