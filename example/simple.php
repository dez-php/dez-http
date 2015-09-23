<?php

error_reporting(1);
ini_set('display_errors', 1);

include_once '../vendor/autoload.php';

$container  = new \Dez\DependencyInjection\Container();

$container->set( 'response', new \Dez\Http\Response() );
$container->set( 'cookies', new \Dez\Http\Cookies() );

$cookies    = $container->get( 'cookies' );

$cookies->set( 'test', 'value', time() + 123123123 );

$container->get( 'response' )->send();

die(var_dump(
    $container->get( 'response' )
));
