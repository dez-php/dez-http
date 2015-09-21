<?php

    include_once '../vendor/autoload.php';

    $request = new \Dez\Http\Request();

    die(var_dump( $request->getRawBody() ));