<?php

    namespace Dez\Http;

    use Dez\Http\Request\RequestInterface;

    class Request implements RequestInterface {

        public function getServerHttp( $key = null, $default = null ) {
            return $this->getServer( "http_$key", $default );
        }

        public function getServer( $key = null, $default = null ) {
            return $this->getFromArray( $_SERVER, strtoupper( $key ), $default );
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
            return $this->getFromArray( $_REQUEST, $key, $default );
        }

        public function getPost( $key = null, $default = null ) {
            return $this->getFromArray( $_POST, $key, $default );
        }

        public function getQuery( $key = null, $default = null ) {
            return $this->getFromArray( $_GET, $key, $default );
        }

        public function getFromArray( array $source, $key, $default = null ) {
            return isset( $source[ $key ] ) ? $source[ $key ] : $default;
        }

        public function getServerIP() {
            return $this->getServer( 'server_addr' );
        }

        public function getClientIP() {
            return $this->getServer( 'remote_addr' );
        }

        public function getRealClientIP() {

            $remoteAddr     = $this->getServerHttp( 'x_forwarded_for' );

            if( ! $remoteAddr ) {
                $remoteAddr = $this->getServerHttp( 'client_ip' );

                if( ! $remoteAddr ) {
                    $remoteAddr = $this->getClientIP();
                }
            }

            if( strpos( $remoteAddr, ',' ) !== false ) {
                $remoteAddr = explode( ',', $remoteAddr )[0];
            }

            return $remoteAddr;
        }

    }