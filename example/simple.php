<?php

    include_once '../vendor/autoload.php';

    $cookies    = new \Dez\Http\Cookies();

$cookies->set( 'test', 'value', time() + 123123123 );

    $cookies->get( 'privatly_auth' )->getValue();
    $cookies->get( 'PHPSESSID' )->getValue();
    $cookies->get( 'csrf_token' )->getValue();

die(var_dump(
    $cookies,
    $cookies->get( 'privatly_auth' ),
    $cookies->get( 'PHPSESSID' ),
    $cookies->get( 'csrf_token' )
));


/*    $request = new \Dez\Http\Request();

    die(var_dump(
        $request,
        $request->getClientIP(),
        $request->getRealClientIP(),
        $request->getSchema(),
        $request->getServerIP(),
        $request->isGet(),
        $request->requestMethod(),
        $request->getUserAgent(),
        $request->getQuery( 'var1' ),
        $request->getQuery()
    ));*/