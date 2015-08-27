<?php

    namespace Dez\Http;

    use Dez\Http\Request\RequestInterface;

    class Request implements RequestInterface {

        public function getServerHttp( $key = null, $default = null ) {
            $key    = 'HTTP_' . $key;
            return $this->getServer( $key, $default );
        }

        public function getServer( $key = null, $default = null ) {
            $key    = strtoupper( $key );
            return isset( $_SERVER[$key] )
                ? $_SERVER[$key]
                : $default;
        }

        public function requestMethod() {
            return strtoupper( $this->getServer( 'request_method' ) );
        }

        public function isPost() {
            return $this->requestMethod() === 'POST';
        }

        public function isAjax() {
            return $this->getServerHttp( 'X_REQUESTED_WITH' ) === 'XMLHttpRequest';
        }

        public function getRawBody() {
            static $rawBody;
            if( ! $rawBody )
                $rawBody    = file_get_contents( 'php://input' );
            return $rawBody;
        }

        public function get( $key = null, $default = null ) {
            return isset( $_GET[$key] )
                ? $_GET[$key]
                : $default;
        }

        public function getPost( $key = null, $default = null ) {
            return isset( $_POST[$key] )
                ? $_POST[$key]
                : $default;
        }

    }