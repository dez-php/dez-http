<?php

    namespace Dez\Http;

    use Dez\Http\Request\RequestInterface;

    /**
     * Class Request
     * @package Dez\Http
     */
    class Request implements RequestInterface {

        /**
         * @return string
         */
        public function requestMethod() {
            return strtoupper( $this->getServer( 'request_method' ) );
        }

        /**
         * @param $key
         * @return bool
         */
        public function has( $key ) {
            return isset( $_REQUEST[ $key ] );
        }

        /**
         * @param $key
         * @return bool
         */
        public function hasQuery( $key ) {
            return isset( $_GET[ $key ] );
        }

        /**
         * @param $key
         * @return bool
         */
        public function hasPost( $key ) {
            return isset( $_POST[ $key ] );
        }

        /**
         * @return string
         */
        public function getRawBody() {
            static $rawBody;
            if( ! $rawBody )
                $rawBody    = file_get_contents( 'php://input' );
            return $rawBody;
        }

        /**
         * @param string $key
         * @param mixed $default
         * @return mixed
         */
        public function get( $key = null, $default = null ) {
            return $this->getFromArray( $_REQUEST, $key, $default );
        }

        /**
         * @param string $key
         * @param mixed $default
         * @return mixed
         */
        public function getPost( $key = null, $default = null ) {
            return $this->getFromArray( $_POST, $key, $default );
        }

        /**
         * @param string $key
         * @param mixed $default
         * @return mixed
         */
        public function getQuery( $key = null, $default = null ) {
            return $this->getFromArray( $_GET, $key, $default );
        }

        /**
         * @param string $key
         * @param mixed $default
         * @return mixed
         */
        public function getServer( $key = null, $default = null ) {
            return $this->getFromArray( $_SERVER, strtoupper( $key ), $default );
        }

        /**
         * @return string
         */
        public function getServerIP() {
            return $this->getServer( 'server_addr' );
        }

        /**
         * @return string
         */
        public function getClientIP() {
            return $this->getServer( 'remote_addr' );
        }

        /**
         * @return string
         */
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

        /**
         * @return string
         */
        public function getUserAgent() {
            return $this->getServerHttp( 'user_agent' );
        }

        /**
         * @return string
         */
        public function getSchema() {
            $schema = $this->getServer( 'https' );
            return ! $schema || $schema === 'off' ? 'http' : 'https';
        }

        /**
         * @param array $source
         * @param $key
         * @param mixed $default
         * @return mixed
         */
        public function getFromArray( array $source, $key, $default = null ) {
            return $key === null ? $source : ( isset( $source[ $key ] ) ? $source[ $key ] : $default );
        }

        /**
         * @param string $key
         * @param mixed $default
         * @return string
         */
        public function getServerHttp( $key = null, $default = null ) {
            return $this->getServer( "http_$key", $default );
        }

        /**
         * @param array $methods
         * @return bool
         */
        public function isMethod( array $methods ) {
            return in_array( $this->requestMethod(), $methods );
        }

        /**
         * @return bool
         */
        public function isAjax() {
            return $this->getServerHttp( 'X_REQUESTED_WITH' ) === 'XMLHttpRequest';
        }

        /**
         * @return bool
         */
        public function isGet() {
            return $this->requestMethod() === 'GET';
        }

        /**
         * @return bool
         */
        public function isPost() {
            return $this->requestMethod() === 'POST';
        }

        /**
         * @return bool
         */
        public function isPut() {
            return $this->requestMethod() === 'PUT';
        }

        /**
         * @return bool
         */
        public function isDelete() {
            return $this->requestMethod() === 'DELETE';
        }

        /**
         * @return bool
         */
        public function isPatch() {
            return $this->requestMethod() === 'PATCH';
        }

        /**
         * @return bool
         */
        public function isHead() {
            return $this->requestMethod() === 'HEAD';
        }

        /**
         * @return bool
         */
        public function isOptions() {
            return $this->requestMethod() === 'OPTIONS';
        }

        /**
         * @return bool
         */
        public function isSecure() {
            return $this->getSchema() === 'https';
        }


    }