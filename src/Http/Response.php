<?php

    namespace Dez\Http;

    use Dez\DependencyInjection\ContainerInterface;
    use Dez\DependencyInjection\InjectableInterface;
    use Dez\Http\Response\Format\ApiJson;
    use Dez\Http\Response\Format\Html;
    use Dez\Http\Response\Format\Json;
    use Dez\Http\Response\Headers;

    class Response implements InjectableInterface, ResponseInterface {

        const RESPONSE_JSON       = 'json';

        const RESPONSE_HTML       = 'html';

        const RESPONSE_API_JSON   = 'api_json';

        /**
         * @var ContainerInterface
         */
        protected $di;

        /**
         * @var string
         */
        protected $format     = self::RESPONSE_HTML;

        /**
         * @var int
         */
        protected $code       = 200;

        /**
         * @var \Dez\Http\Response\HeadersInterface
         */
        protected $headers    = null;

        /**
         * @var \Dez\Http\CookiesInterface
         */
        protected $cookies    = null;

        /**
         * @var null
         */
        protected $body       = null;

        public function __construct() {
            $this->setHeaders( new Headers() );
        }

        /**
         * @return ContainerInterface
         */
        public function getDi() {
            return $this->di;
        }

        /**
         * @param mixed $di
         * @return static
         */
        public function setDi( ContainerInterface $di ) {
            $this->di = $di;
            return $this;
        }

        public function setFormat( $format = self::RESPONSE_HTML ) {
            if( ! in_array( strtolower( $format ), [ self::RESPONSE_HTML, self::RESPONSE_JSON, self::RESPONSE_API_JSON ] ) ) {
                throw new Exception( 'Setting bad response format' );
            }
            $this->format = $format;
            return $this;
        }

        public function getFormat() {
            return $this->format;
        }

        public function setCode( $code = 200 ) {
            $this->code = (int) $code;
            return $this;
        }

        public function getCode() {
            return;
        }

        public function setHeader( $name = '', $value ) {
            $this->headers->set( $name, $value, true );
            return $this;
        }

        public function addHeader( $name = '', $value ) {
            $this->headers->set( $name, $value, false );
            return $this;
        }

        public function hasHeader( $name ) {
            return $this->headers->has( $name );
        }

        public function getHeader( $name ) {
            return $this->headers->get( $name );
        }

        /**
         * @return \Dez\Http\Response\Headers
         */
        public function getHeaders() {
            return $this->headers;
        }

        /**
         * @param \Dez\Http\Response\Headers $headers
         * @return static
         */
        public function setHeaders( Headers $headers ) {
            $this->headers = $headers;
            return $this;
        }

        /**
         * @return CookiesInterface
         */
        public function getCookies() {
            return $this->cookies;
        }

        /**
         * @param CookiesInterface $cookies
         * @return static
         */
        public function setCookies( CookiesInterface $cookies ) {
            $this->cookies = $cookies;
            return $this;
        }

        public function getBody() {
            return $this->body;
        }

        public function send() {
            if( $this->getFormat() == self::RESPONSE_HTML ) {
                $formatter = new Html( $this );
            } else if( $this->getFormat() == self::RESPONSE_JSON ) {
                $formatter = new Json( $this );
            } else if( $this->getFormat() == self::RESPONSE_API_JSON ) {
                $formatter = new ApiJson( $this );
            } else {
                throw new Exception( 'Response cannot be possible because have bad format' );
            }

            $formatter->process();

            $this->sendHeaders();
            $this->sendBody();
        }

        public function sendHeaders() {

        }

        public function sendBody() {
            print $this->getBody();
        }

    }