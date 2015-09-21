<?php

    namespace Dez\Http\Response;

    /**
     * Class Headers
     * @package Dez\Http\Response
     */
    class Headers implements HeadersInterface {

        /**
         * @var array
         */
        protected $headers  = [];

        /**
         * @param $name
         * @return bool
         */
        public function has( $name ) {
            return isset( $this->headers[ $name ] );
        }

        /**
         * @param $name
         * @return null
         */
        public function get( $name ) {
            return $this->has( $name ) ? $this->headers[ $name ] : null;
        }

        /**
         * @param $name
         * @param $value
         * @param bool|true $replace
         * @return $this
         */
        public function set( $name, $value, $replace = true ) {
            if( $replace === true ) {
                $this->reset( $name );
            }
            $this->headers[ $name ][]   = $value;
            return $this;
        }

        /**
         * @param null $name
         * @return $this
         */
        public function reset( $name = null ) {
            if( $name === null ) {
                $this->headers          = [];
            } else {
                $this->headers[ $name ] = [];
            }
            return $this;
        }

        /**
         *
         */
        public function send() {

        }

    }