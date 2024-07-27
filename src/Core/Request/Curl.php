<?php

namespace Carpenstar\ByBitAPI\Core\Request;

use Carpenstar\ByBitAPI\Core\Auth\Credentials;
use Carpenstar\ByBitAPI\Core\Auth\SignGenerator;

class Curl
{
    protected bool $isNeedAuthorization;
    protected Credentials $credentials;
    private array $curlHeaders = [];
    private array $curlOptions = [];


    public function init(bool $isNeedAuthorization, Credentials $credentials): self
    {
        $this->isNeedAuthorization = $isNeedAuthorization;
        $this->credentials = $credentials;

        $this->addCurlOpt(CURLOPT_RETURNTRANSFER, true);
        $this->addCurlHeader('Content-Type', 'application/json');

        return $this;
    }

    /**
     * @param int $option
     * @param $value
     * @return $this
     */
    protected function addCurlOpt(int $option, $value): self
    {
        $this->curlOptions[$option] = $value;
        return $this;
    }

    /**
     * @param string $header
     * @param string $value
     * @return $this
     */
    protected function addCurlHeader(string $header, string $value): self
    {
        $this->curlHeaders[$header] = "$header: $value";
        return $this;
    }

    protected function query(): array
    {
        $curl = curl_init();

        $this->addCurlOpt(CURLOPT_HTTPHEADER, $this->curlHeaders);

        array_walk($this->curlOptions, function ($value, $option, $curl) {
            curl_setopt($curl, $option, $value);
        }, $curl);

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);

        return $response;
    }

    /**
     * Создание заголовков авторизации для приватных эндпоинтов
     * @param string $queryString
     * @return void
     */
    protected function auth(string $queryString): void
    {
        if ($this->isNeedAuthorization) {
            $timestamp = time() * 1000;
            $sign = SignGenerator::make($this->credentials, $queryString, $timestamp, 5000);
            $this->addCurlHeader('X-BAPI-API-KEY', $this->credentials->getApiKey());
            $this->addCurlHeader('X-BAPI-SIGN', $sign);
            $this->addCurlHeader('X-BAPI-SIGN-TYPE', 2);
            $this->addCurlHeader('X-BAPI-TIMESTAMP', $timestamp);
            $this->addCurlHeader('X-BAPI-RECV-WINDOW', 5000);
        }
    }

}
