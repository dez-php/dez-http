<?php

namespace HttpClientTest;

use Dez\Http\Client\Provider\Curl;

error_reporting(1);
ini_set('display_errors', 1);

include_once '../vendor/autoload.php';

$filepath = realpath('./test.jpg');

$curl = new Curl();
$curl->uri('http://fs.local/upload');

$response = $curl->post([
    'upload_type' => 'local',
    'file' => Curl::file($filepath),
    'name' => 'rick and morty wallpaper'
]);

var_dump($response->getJsonBody()->response, $response->getContentType(), $response->getHttpCode());
