<?php

namespace BeycanPress\HTTP;

final class Client
{

    /**
     * Base API url
     * @var string
     */
    private $baseUrl = null;

    /**
     * cURL process infos
     * @var mixed
     */
    private $info;

    /**
     * cURL process errors
     * @var string
     */
    private $error;

    /**
     * @var array
     */
    private $methods = [
        "GET",
        "HEAD",
        "POST",
        "PUT",
        "DELETE",
        "CONNECT",
        "OPTIONS",
        "TRACE",
        "PATCH",
    ];
    /**
     * Default options
     * @var array
     */
    private $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [],
    ];

    /**
     * @param string $url
     * @return Client
     */
    public function setBaseUrl(string $url) : Client
    {
        $this->baseUrl = $url;
        return $this;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return Client
     */
    public function addOption($key, $value) : Client
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * @param array $options
     * @return Client
     */
    public function addoptions(array $options) : Client
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return Client
     */
    public function addHeader(string $key, string $value) : Client
    {
        $this->options[CURLOPT_HTTPHEADER][] = $key . ': ' . $value;
        return $this;
    }

    /**
     * @param array $headers
     * @return Client
     */
    public function addHeaders(array $headers) : Client
    {
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return string
     */
    public function getError() : string
    {
        return $this->error;
    }

    /**
     *
     * @param string $string
     * @return mixed
     */
    private function ifIsJson(string $string) 
    {
        $json = json_decode($string);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        } else {
            return $string;
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (!in_array(strtoupper($name), $this->methods)) {
            throw new \Exception("Method not found");
        }
        
        $this->addOption(CURLOPT_CUSTOMREQUEST, strtoupper($name));
        return $this->beforeSend(...$arguments);
    }

    /**
     * @param string $url
     * @param array $data
     * @param boolean $raw
     * @return mixed
     */
    private function beforeSend(string $url, array $data = [], bool $raw = false)
    {
        if (!empty($data)) {
            if ($raw) {
                $data = json_encode($data);
                $data = <<<DATA
                    $data
                DATA;
            } else {
                $data = http_build_query($data);
            }
            $this->addOption(CURLOPT_POSTFIELDS, $data);
        }

        return $this->send($url);
    }

    /**
     * @param string $url
     * @return mixed
     */
    private function send(string $url)
    {
        if (!is_null($this->baseUrl)) {
            $url = $this->baseUrl . $url;
        }

        // Inıt
        $curl = curl_init($url);
        
        // Set options
        curl_setopt_array($curl, $this->options);

        // Exec
        $result = curl_exec($curl);

        // Get some information
        $this->info = curl_getinfo($curl);
        $this->error = curl_error($curl);

        // Close
        curl_close($curl);

        if (is_string($result)) {
            $result = $this->ifIsJson($result);
        }

        return $result;
    }
}