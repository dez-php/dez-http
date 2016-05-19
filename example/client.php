<?php

namespace HttpClientTest;

use Dez\Http\Client\Provider\Curl;

error_reporting(1);
ini_set('display_errors', 1);

include_once '../vendor/autoload.php';

$curl = new Curl();

$response = $curl->uri('http://fs.local/upload')->post([
    'upload_type' => 'local',
    'file' => Curl::file(realpath('./blank.txt'))
]);

var_dump($response->getJsonBody(), $response->getContentType(), $response->getHttpCode());
