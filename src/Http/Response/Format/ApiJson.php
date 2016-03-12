<?php

namespace Dez\Http\Response\Format;

use Dez\Http\Response\Format;

/**
 * Class ApiJson
 * @package Dez\Http\Response\Format
 */
class ApiJson extends Format
{

    /**
     * @return \Dez\Http\Response
     */
    public function process()
    {
        $this->response->setHeader('Content-type', 'application/json');
        $this->response->setContent(json_encode($this->createResponseBody(), JSON_PRETTY_PRINT));

        return $this->response;
    }

    /**
     * @return array|null
     * @throws \Dez\Http\Exception
     */
    private function createResponseBody()
    {
        $response = $this->response->getContent();
        $statusCode = $this->response->getStatusCode();

        $response = !is_array($response) ? [$response] : $response;

        $response['response-status'] = [
            'code' => $statusCode,
            'status' => $this->response->getStatusMessage($statusCode),
            'memory' => $this->memoryUsage(),
        ];

        return $response;
    }

    /**
     * @return string
     */
    private function memoryUsage()
    {
        $bytes = memory_get_usage();

        $name = 'B';

        if ($bytes > 1024) {
            $name = 'K';
            $bytes = $bytes / 1024;
        } else {
            if ($bytes > (1024 * 1024)) {
                $name = 'M';
                $bytes = $bytes / 1024;
            }
        }

        return "$bytes $name";
    }

}