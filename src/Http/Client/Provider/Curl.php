<?php

namespace Dez\Http\Client\Provider;

use Dez\Http\Client\HttpRequest;
use Dez\Http\Client\HttpRequestException;

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
     * @return $this
     */
    public function send($method = HttpRequest::METHOD_GET)
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, $method);
        $this->setOption(CURLOPT_URL, $this->uri->full());
        $this->setOption(CURLOPT_HTTPHEADER, "X-Request-Timestamp: ". time());

        return curl_exec($this->handle);
    }

    /**
     * @param array $params
     * @return Curl
     */
    public function post(array $params = [])
    {
        $this->setOption(CURLOPT_POSTFIELDS, $params);
        $this->setOption(CURLOPT_POST, true);

        return $this->send(HttpRequest::METHOD_POST);
    }

    /**
     * @param array $params
     * @return Curl
     */
    public function get(array $params = [])
    {
        $this->setOption(CURLOPT_HTTPGET, true);
        
        foreach ($params as $name => $value) {
            $this->uri->setQuery($name, $value);
        }

        return $this->send(HttpRequest::METHOD_GET);
    }

}