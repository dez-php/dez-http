<?php

namespace Dez\Http\Client\Provider;

use Dez\Http\Client\HttpRequest;
use Dez\Http\Client\HttpRequestException;
use Dez\Http\Client\Response;

class Curl extends HttpRequest {

    /**
     * @var
     */
    private $handle;

    /**
     * Curl constructor.
     */
    public function __construct()
    {
        parent::__construct();
        
        if(! extension_loaded('curl')) {
            throw new HttpRequestException("cURL extension not available");
        }
        
        $this->handle = curl_init();
        
        $this->setOptions([
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_AUTOREFERER     => true,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_MAXREDIRS       => 20,
            CURLOPT_PROTOCOLS       => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_USERAGENT       => sprintf(static::USER_AGENT, static::VERSION, 'cURL'),
            CURLOPT_CONNECTTIMEOUT  => 10,
            CURLOPT_TIMEOUT         => 15
        ]);
    }

    /**
     * Curl destructor.
     */
    public function __destruct()
    {
        curl_close($this->handle);
    }

    /**
     * @param integer $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->setOption(CURLOPT_TIMEOUT, $timeout);

        return $this;
    }

    /**
     * @param integer $timeout
     * @return $this
     */
    public function setConnectTimeout($timeout)
    {
        $this->setOption(CURLOPT_CONNECTTIMEOUT, $timeout);

        return $this;
    }

    /**
     * @param $option
     * @param $value
     * @return $this
     */
    public function setOption($option, $value)
    {
        curl_setopt($this->handle, $option, $value);
        
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options = [])
    {
        curl_setopt_array($this->handle, $options);

        return $this;
    }

    /**
     * @param string $method
     * @return Response
     * @throws HttpRequestException
     */
    public function send($method = HttpRequest::METHOD_GET)
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, $method);
        $this->setOption(CURLOPT_URL, $this->uri->full());
        $this->setOption(CURLOPT_HTTPHEADER, ["X-cURL-Request: ". time()]);

        $responseContent = curl_exec($this->handle);
        $responseInfo = curl_getinfo($this->handle);

        if(curl_errno($this->handle) !== 0) {
            throw new HttpRequestException("cURL failed with error: ". curl_error($this->handle));
        }

        $contentType = $responseInfo['content_type'];
        $statusCode = $responseInfo['http_code'];
        
        return new Response($responseContent, $statusCode, $contentType);
    }

    public function post(array $params = [])
    {
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $params);

        return $this->send(HttpRequest::METHOD_POST);
    }
    
    public function get(array $params = [])
    {
        $this->setOption(CURLOPT_HTTPGET, true);
        
        foreach ($params as $name => $value) {
            $this->uri->setQuery($name, $value);
        }

        return $this->send(HttpRequest::METHOD_GET);
    }

    public static function file($filepath = null)
    {
        if(! file_exists($filepath)) {
            throw new HttpRequestException();
        }

        return curl_file_create($filepath);
    }

}