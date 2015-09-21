<?php

    namespace Dez\Http\Response\Format;

    use Dez\Http\Response\Format;

    class ApiJson extends Format {

        public function process() {
            $this->response->setHeader( 'Content-type', 'application/json' );
            $response                   = $this->response->getBody();
            $response                   = ! is_array( $response ) ? [ $response ] : $response;
            $response['http_code']      = $this->response->getCode();
            $response['execute_time']   = \Dez::getTimeDiff();
            $response['memory_use']     = \Dez::getMemoryUse();
            $this->response->setBody( json_encode( $response, JSON_PRETTY_PRINT ) );
        }

    }