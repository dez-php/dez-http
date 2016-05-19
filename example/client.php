<?php

namespace HttpClientTest;

use Dez\Http\Client\Provider\Curl;

error_reporting(1);
ini_set('display_errors', 1);

include_once '../vendor/autoload.php';

$curl = new Curl();

var_dump($curl->uri('http://fs.local/upload')->post([
    'upload_type' => 'direct-link'
]));
