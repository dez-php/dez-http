<?php

    namespace Dez\Http;

    use Dez\Http\Request\RequestInterface;

    class Request implements RequestInterface {



        public function requestMethod() {
            return strtoupper( $this->getServer( 'request_method' ) );
        }

        public function has( $key ) {
            return isset( $_REQUEST[ $key ] );
        }

        public function hasQuery( $key ) {
            return isset( $_GET[ $key ] );
        }

        public function hasPost( $key ) {
            return isset( $_POST[ $key ] );
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

        public function getServer( $key = null, $default = null ) {
            return $this->getFromArray( $_SERVER, strtoupper( $key ), $default );
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

        public function getUserAgent() {
            return $this->getServerHttp( 'user_agent' );
        }

        public function getSchema() {
            $schema = $this->getServer( 'https' );
            return ! $schema || $schema === 'off' ? 'http' : 'https';
        }

        public function getFromArray( array $source, $key, $default = null ) {
            return isset( $source[ $key ] ) ? $source[ $key ] : $default;
        }

        public function getServerHttp( $key = null, $default = null ) {
            return $this->getServer( "http_$key", $default );
        }

        public function isMethod( array $methods ) {
            return in_array( $this->requestMethod(), $methods );
        }

        public function isAjax() {
            return $this->getServerHttp( 'X_REQUESTED_WITH' ) === 'XMLHttpRequest';
        }

        public function isGet() {
            return $this->requestMethod() === 'GET';
        }

        public function isPost() {
            return $this->requestMethod() === 'POST';
        }

        public function isPut() {
            return $this->requestMethod() === 'PUT';
        }

        public function isDelete() {
            return $this->requestMethod() === 'DELETE';
        }

        public function isPatch() {
            return $this->requestMethod() === 'PATCH';
        }

        public function isHead() {
            return $this->requestMethod() === 'HEAD';
        }

        public function isOptions() {
            return $this->requestMethod() === 'OPTIONS';
        }

        public function isSecure() {
            return $this->getSchema() === 'https';
        }


    }