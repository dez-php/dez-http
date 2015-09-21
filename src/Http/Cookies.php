<?php

    namespace Dez\Http;

    use Dez\Http\Cookies\Cookie;

    class Cookies implements CookiesInterface {

        protected $cookies  = [];

        public function getCookie() {

        }

        public function addCookie( Cookie $cookie ) {
            $this->cookies[]    = $cookie;
            return $this;
        }

        /**
         * @return \Dez\Http\Cookies\Cookie[]
         */
        public function getCookies() {
            return $this->cookies;
        }

        /**
         * @param \Dez\Http\Cookies\Cookie[] $cookies
         * @return static
         */
        public function setCookies( $cookies ) {
            $this->cookies = $cookies;
            return $this;
        }



    }