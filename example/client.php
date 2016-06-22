<?php

namespace HttpClientTest;

use Dez\Http\Client\Provider\Curl;

error_reporting(1);
ini_set('display_errors', 1);

include_once '../vendor/autoload.php';

$filepath = realpath('./test.jpg');
$client = 'site1';
$privateKey = 'EInXMMSKu3VmAawB';

$postData = [
    'upload_type' => 'local',
    'name' => 'rick and morty wallpaper',
    'category_id' => 1,
];

$hash = md5($client . $privateKey);
$sign = sha1(implode($hash, $postData));

$postData['file'] = Curl::file($filepath);

$curl = new Curl();
$curl->uri('http://fs.local/upload');
$curl->setTimeout(3);

$uri = $curl->getUri();

$uri->setQuery('client', $client);
$uri->setQuery('sign1', $sign);

$response = $curl->post($postData);

die($response->getBody());

var_dump($response->getBody(), $response->getContentType(), $response->getHttpCode());
