<?php

    namespace Dez\Http;

    use Dez\DependencyInjection\ContainerInterface;
    use Dez\DependencyInjection\InjectableInterface;
    use Dez\Http\Cookies\Cookie;

    class Cookies implements InjectableInterface, CookiesInterface {


        /**
         * @var ContainerInterface
         */
        protected $di;

        /**
         * @var array
         */
        protected $cookies  = [];

        /**
         * @return ContainerInterface
         */
        public function getDi() {
            return $this->di;
        }

        /**
         * @param ContainerInterface $di
         * @return static
         */
        public function setDi( ContainerInterface $di ) {
            $this->di = $di;
            return $this;
        }

        /**
         * @param $name
         * @param $value
         * @param int $expired
         * @param string $path
         * @param null $domain
         * @param null $secure
         * @param null $httpOnly
         * @return $this
         */
        public function set( $name, $value, $expired = 0, $path = '/', $domain = null, $secure = null, $httpOnly = null ) {
            $this->addCookie( $name, new Cookie( $name, $value, $expired, $path, $domain, $secure, $httpOnly ) );
            return $this;
        }

        /**
         * @param $name string
         * @return Cookie
         */
        public function get( $name ) {

            if( $this->has( $name ) ) {
                $cookie     = $this->cookies[ $name ];
            } else {
                $cookie     = new Cookie( $name );
                $this->addCookie( $name, $cookie );
            }

            return $cookie;
        }

        /**
         * @param $name string
         * @return bool
         */
        public function has( $name ) {
            return isset( $this->cookies[ $name ] );
        }

        /**
         * @param $name string
         * @return $this
         */
        public function delete( $name ) {

            if( $this->has( $name ) ) {
                $this->get( $name )->delete();
            }

            return $this;
        }

        /**
         * @param $name
         * @param Cookie $cookie
         * @return $this
         */
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