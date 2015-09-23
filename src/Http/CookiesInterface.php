<?php

    namespace Dez\Http;

    interface CookiesInterface {

        public function set( $name, $value, $expired, $path, $domain, $secure, $httpOnly = false );

        public function get( $name );

        public function has( $name );

        public function delete( $name );

        public function send();

    }