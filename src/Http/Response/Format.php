<?php

    namespace Dez\Http\Response;

    use Dez\Http\Response;

    abstract class Format implements FormatInterface {

        protected $response = null;

        public function __construct( Response $response = null ) {
            $this->response = $response;
        }

    }