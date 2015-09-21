<?php

    include_once '../vendor/autoload.php';

    $request = new \Dez\Http\Request();

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
    ));