<?php

    namespace Dez\Http;

    use Dez\DependencyInjection\ContainerInterface;
    use Dez\DependencyInjection\InjectableInterface;
    use Dez\Http\Response\Format\ApiJson;
    use Dez\Http\Response\Format\Html;
    use Dez\Http\Response\Format\Json;
    use Dez\Http\Response\Headers;

    /**
     * Class Response
     * @package Dez\Http
     */
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
        protected $bodyFormat   = self::RESPONSE_HTML;

        /**
         * @var int
         */
        protected $statusCode   = 200;

        /**
         * @var null
         */
        protected $content      = null;

        /**
         * @var \Dez\Http\Response\HeadersInterface
         */
        protected $headers      = null;

        /**
         * @var bool
         */
        protected $enableBody   = true;


        /**
         * @param null $content
         * @param int $statusCode
         * @param null $statusMessage
         */
        public function __construct( $content = null, $statusCode = 200, $statusMessage = null ) {
            $this->setHeaders( new Headers() );
            $this->setHeader( 'X-Service-Powered', 'HTTP Service v1.0, dez-php' );
            if( $content !== null ) {
                $this->setContent( $content )->setStatusCode( $statusCode, $statusMessage );
            }
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

        /**
         * @return string
         */
        public function getBodyFormat() {
            return $this->bodyFormat;
        }

        /**
         * @param string $bodyFormat
         * @return static
         */
        public function setBodyFormat( $bodyFormat ) {
            $this->bodyFormat = $bodyFormat;
            return $this;
        }

        /**
         * @return int
         */
        public function getStatusCode() {
            return $this->statusCode;
        }

        /**
         * @param $statusCode
         * @param null $statusMessage
         * @return $this
         * @throws Exception
         */
        public function setStatusCode( $statusCode, $statusMessage = null )  {

            if( $statusMessage === null ) {
                $statusMessage  = $this->getStatusMessage( $statusCode );
            }

            $this->setRawHeader( "HTTP/1.1 $statusCode $statusMessage" );
            $this->setHeader( 'Status', "$statusCode $statusMessage" );

            $this->statusCode = $statusCode;

            return $this;
        }

        /**
         * @param string $url
         * @return $this
         */
        public function redirect( $url = '/' ) {
            $this->setEnableBody( false );
            $this->setStatusCode( 302 );
            $this->getHeaders()->set( 'Location', $url );
            return $this;
        }

        /**
         * @return null
         */
        public function getContent() {
            return $this->content;
        }

        /**
         * @param null $content
         * @return static
         */
        public function setContent( $content ) {
            $this->content = $content;
            return $this;
        }

        /**
         * @param null $content
         * @return static
         */
        public function appentContent( $content ) {
            $this->content = $this->getContent() . $content;
            return $this;
        }

        /**
         * @param null $content
         * @return static
         */
        public function prependContent( $content ) {
            $this->content = $content . $this->getContent();
            return $this;
        }

        /**
         * @param $header
         * @return $this
         */
        public function setRawHeader( $header ) {
            $this->headers->setRaw( $header );
            return $this;
        }

        /**
         * @param string $name
         * @param $value
         * @return $this
         */
        public function setHeader( $name = '', $value ) {
            $this->headers->set( $name, $value, true );
            return $this;
        }

        /**
         * @param string $name
         * @param $value
         * @return $this
         */
        public function addHeader( $name = '', $value ) {
            $this->headers->set( $name, $value, false );
            return $this;
        }

        /**
         * @param $name
         * @return mixed
         */
        public function hasHeader( $name ) {
            return $this->headers->has( $name );
        }

        /**
         * @param $name
         * @return mixed
         */
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
         * @return $this
         */
        public function resetHeaders() {
            $this->getHeaders()->reset();
            return $this;
        }

        /**
         * @return $this
         */
        public function sendHeaders() {
            $this->getHeaders()->send();
            return $this;
        }

        /**
         * @return boolean
         */
        public function isEnableBody() {
            return $this->enableBody;
        }

        /**
         * @param boolean $enableBody
         * @return $this
         */
        public function setEnableBody( $enableBody ) {
            $this->enableBody = $enableBody;
            return $this;
        }

        /**
         * @return $this
         * @throws Exception
         */
        public function sendCookies() {

            $container  = $this->getDi();
            if( ! $container || ! ( $container instanceof ContainerInterface ) ) {
                throw new Exception( 'DependencyInjection require for response service' );
            }

            /** @var $cookies CookiesInterface */
            $cookies    = $container->get( 'cookies' );
            if( ! $cookies || ! ( $cookies instanceof CookiesInterface ) ) {
                throw new Exception( 'Cookies service is not registered in DependencyInjection' );
            }

            $cookies->send();

            return $this;
        }

        /**
         * @return $this
         */
        public function sendContent() {
            echo $this->getContent();
            return $this;
        }

        /**
         * @throws Exception
         */
        public function handlerContent() {

            if( $this->getBodyFormat() === Response::RESPONSE_HTML ) {
                $formatter  = new Html( $this );
            } else if( $this->getBodyFormat() === Response::RESPONSE_JSON ) {
                $formatter  = new Json( $this );
            } else if( $this->getBodyFormat() === Response::RESPONSE_API_JSON ) {
                $formatter  = new ApiJson( $this );
            } else {
                throw new Exception( 'Bad setting body-format for response content' );
            }

            $formatter->process();

            return $this;
        }

        /**
         * @return $this
         * @throws Exception
         */
        public function send() {
            $this->handlerContent()->sendHeaders()->sendCookies()->sendContent();
            return $this;
        }

        /**
         * @param int $statusCode
         * @return string
         * @throws Exception
         */
        public function getStatusMessage( $statusCode = 0 ) {

            $statusCodes    = [
                100 => "Continue",
                101 => "Switching Protocols",
                102 => "Processing",
                200 => "OK",
                201 => "Created",
                202 => "Accepted",
                203 => "Non-Authoritative Information",
                204 => "No Content",
                205 => "Reset Content",
                206 => "Partial Content",
                207 => "Multi-status",
                208 => "Already Reported",
                300 => "Multiple Choices",
                301 => "Moved Permanently",
                302 => "Found",
                303 => "See Other",
                304 => "Not Modified",
                305 => "Use Proxy",
                306 => "Switch Proxy",
                307 => "Temporary Redirect",
                400 => "Bad Request",
                401 => "Unauthorized",
                402 => "Payment Required",
                403 => "Forbidden",
                404 => "Not Found",
                405 => "Method Not Allowed",
                406 => "Not Acceptable",
                407 => "Proxy Authentication Required",
                408 => "Request Time-out",
                409 => "Conflict",
                410 => "Gone",
                411 => "Length Required",
                412 => "Precondition Failed",
                413 => "Request Entity Too Large",
                414 => "Request-URI Too Large",
                415 => "Unsupported Media Type",
                416 => "Requested range not satisfiable",
                417 => "Expectation Failed",
                418 => "I'm a teapot",
                422 => "Unprocessable Entity",
                423 => "Locked",
                424 => "Failed Dependency",
                425 => "Unordered Collection",
                426 => "Upgrade Required",
                428 => "Precondition Required",
                429 => "Too Many Requests",
                431 => "Request Header Fields Too Large",
                500 => "Internal Server Error",
                501 => "Not Implemented",
                502 => "Bad Gateway",
                503 => "Service Unavailable",
                504 => "Gateway Time-out",
                505 => "HTTP Version not supported",
                506 => "Variant Also Negotiates",
                507 => "Insufficient Storage",
                508 => "Loop Detected",
                511 => "Network Authentication Required",
            ];

            if( ! isset( $statusCodes[ $statusCode ] ) ) {
                throw new Exception( "The incorrect status code. In this code [$statusCode] there is no message" );
            }

            return $statusCodes[ $statusCode ];

        }

    }