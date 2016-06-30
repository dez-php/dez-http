<?php

namespace HttpClientTest;

use Dez\Http\Client\Provider\Curl;

error_reporting(1);
ini_set('display_errors', 1);

include_once '../vendor/autoload.php';

$filepath = realpath('./test.jpg');

$curl = new Curl();
$curl->uri('http://file-storage.local/upload/dump');
$curl->setTimeout(3);

$uri = $curl->getUri();

$uri->setQuery('token', md5(time()));

$response = $curl->post([
    'upload_type' => 'local',
    'file' => Curl::file($filepath),
    'name' => 'rick and morty wallpaper',
    'category_id' => 13,
]);

var_dump($response->getBody(), $response->getContentType(), $response->getHttpCode());
