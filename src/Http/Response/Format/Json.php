<?php

    namespace Dez\Http\Response\Format;

    use Dez\Http\Response\Format;

    class Json extends Format {

        public function process() {
            $this->response->setHeader( 'Content-type', 'application/json' );
            $this->response->setBody( json_encode( $this->response->getBody(), true ) );
        }

    }