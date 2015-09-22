<?php

    namespace Dez\Http\Cookies;

    class Cookie implements CookieInterface {

        protected $name     = '';

        protected $value    = '';

        protected $expired  = '';

        protected $path     = '/';

        protected $domain   = '';

        protected $secure   = false;

        protected $httpOnly = false;

        public function __construct( $name, $value = null, $expired = 0, $path = '/', $domain = null, $secure = null, $httpOnly = null ) {
            $this->setName( $name );
            $this->setValue( $value );
            $this->setExpired( $expired );
            $this->setPath( $path );
            $this->setDomain( $domain );
            $this->setSecure( $secure );
            $this->setHttpOnly( $httpOnly );
        }

        /**
         * @return string
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @param string $name
         * @return static
         */
        public function setName( $name ) {
            $this->name = $name;
            return $this;
        }

        /**
         * @return string
         */
        public function getValue() {

            if( isset( $_COOKIE[ $this->getName() ] ) ) {
                $this->setValue( $_COOKIE[ $this->getName() ] );
            }

            return $this->value;
        }

        /**
         * @param string $value
         * @return static
         */
        public function setValue( $value ) {
            $this->value = $value;
            return $this;
        }

        /**
         * @return string
         */
        public function getExpired() {
            return $this->expired;
        }

        /**
         * @param string $expired
         * @return static
         */
        public function setExpired( $expired ) {
            $this->expired = date( 'D, d-M-Y H:i:s', $expired ) . ' GMT';
            return $this;
        }

        /**
         * @return string
         */
        public function getPath() {
            return $this->path;
        }

        /**
         * @param string $path
         * @return static
         */
        public function setPath( $path ) {
            $this->path = $path;
            return $this;
        }

        /**
         * @return string
         */
        public function getDomain() {
            return $this->domain;
        }

        /**
         * @param string $domain
         * @return static
         */
        public function setDomain( $domain ) {
            $this->domain = $domain;
            return $this;
        }

        /**
         * @return boolean
         */
        public function isSecure() {
            return $this->secure;
        }

        /**
         * @param boolean $secure
         * @return static
         */
        public function setSecure( $secure ) {
            $this->secure = $secure;
            return $this;
        }

        /**
         * @return boolean
         */
        public function isHttpOnly() {
            return $this->httpOnly;
        }

        /**
         * @param boolean $httpOnly
         * @return static
         */
        public function setHttpOnly( $httpOnly ) {
            $this->httpOnly = $httpOnly;
            return $this;
        }

        /**
         * @return string
         */
        public function __toString() {
            return $this->getValue();
        }


    }