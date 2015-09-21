<?php

    namespace Dez\Http\Response;

    interface HeadersInterface {

        public function has( $name );

        public function get( $name );

        public function set( $name, $value );

        public function reset();

        public function send();

    }