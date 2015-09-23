<?php

    namespace Dez\Http\Response\Format;

    use Dez\Http\Response\Format;

    /**
     * Class ApiJson
     * @package Dez\Http\Response\Format
     */
    class ApiJson extends Format {

        /**
         * @return \Dez\Http\Response
         */
        public function process() {

            $this->response->setHeader( 'Content-type', 'application/json' );
            $response   = $this->response->getContent();

            $response   = ! is_array( $response ) ? [ $response ] : $response;

            $statusCode = $this->response->getStatusCode();

            $response['http_code']      = $statusCode;
            $response['http_status']    = $this->response->getStatusMessage( $statusCode );

            $response['memory_use']     = memory_get_usage( true ) / 1024 . ' kb';

            $this->response->setContent( json_encode( $response, JSON_PRETTY_PRINT ) );

            return $this->response;
        }

    }