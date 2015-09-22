<?php

    namespace Dez\Http;

    use Dez\Http\Cookies\Cookie;

    class Cookies implements CookiesInterface {

        protected $cookies  = [];

        public function set( $name, $value, $expired = 0, $path = '/', $domain = null, $secure = null, $httpOnly = null ) {
            $this->addCookie( $name, new Cookie( $name, $value, $expired, $path, $domain, $secure, $httpOnly ) );
            return $this;
        }

        public function get( $name ) {
            if( $this->has( $name ) ) {
                $cookie     = $this->cookies[ $name ];
            } else {
                $cookie     = new Cookie( $name );
                $this->addCookie( $name, $cookie );
            }
            return $cookie;
        }

        public function has( $name ) {
            return isset( $this->cookies[ $name ] );
        }

        public function delete() {
            return $this;
        }

        public function addCookie( $name, Cookie $cookie ) {
            $this->cookies[ $name ]    = $cookie;
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