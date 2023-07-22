<?php
namespace Carpenstar\ByBitAPI\Core\Request;

use Carpenstar\ByBitAPI\Core\Auth\Credentials;
use Carpenstar\ByBitAPI\Core\Auth\SignGenerator;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
use Carpenstar\ByBitAPI\Core\Response\CurlResponse;

class Curl
{
    /**
     * @var string $host
     */
    protected static $host;

    /**
     * @var string $apiKey
     */
    protected static $apiKey;

    /**
     * @var string $secret
     */
    protected static $secret;

    /**
     * @var Curl[] $instance
     */
    protected static array $instance = [];

    protected bool $isNeedAuthorization;

    private array $curlHeaders = [];

    private array $curlOptions = [];

    private function __construct()
    {
        static::$host = Credentials::getHost();
        static::$apiKey = Credentials::getApiKey();
        static::$secret = Credentials::getSecret();
    }

    public static function getInstance(bool $isNeedAuthorization)
    {
        if (empty(self::$instance[static::class])) {
            self::$instance[static::class] = new static();
        }
        self::$instance[static::class]->addCurlOpt(CURLOPT_RETURNTRANSFER, true);
        self::$instance[static::class]->addCurlHeader('Content-Type', 'application/json');
        self::$instance[static::class]->isNeedAuthorization = $isNeedAuthorization;
        return self::$instance[static::class];
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
        $this->curlHeaders[$header] = $value;
        return $this;
    }

    /**
     * @return IResponseInterface
     */
    protected function execute(): IResponseInterface
    {
        $curl = curl_init();

        if (!empty($this->curlHeaders)) {
            $headers = [];
            foreach ($this->curlHeaders as $headerKey => $headerValue) {
                $headers[] = "$headerKey: $headerValue";
            }

            $this->addCurlOpt(CURLOPT_HTTPHEADER, $headers);
        }

        if (!empty($this->curlOptions)) {
            foreach ($this->curlOptions as $option => $value) {
                curl_setopt($curl, $option, $value);
            }
        }

        $response = new CurlResponse(curl_exec($curl));

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
        $timestamp = time() * 1000;
        $sign = SignGenerator::make(static::$apiKey, static::$secret, $queryString, $timestamp, 5000);
        $this->addCurlHeader('X-BAPI-API-KEY', static::$apiKey);
        $this->addCurlHeader('X-BAPI-SIGN', $sign);
        $this->addCurlHeader('X-BAPI-SIGN-TYPE', 2);
        $this->addCurlHeader('X-BAPI-TIMESTAMP', $timestamp);
        $this->addCurlHeader('X-BAPI-RECV-WINDOW', 5000);
    }

}
