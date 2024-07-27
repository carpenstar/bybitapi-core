<?php

namespace Carpenstar\ByBitAPI\Core\Request;

use Carpenstar\ByBitAPI\Core\Enums\EnumHttpMethods;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseHandlerInterface;

class PostRequest extends Curl
{
    /**
     * @param string $endpoint
     * @param array $params
     * @return IResponseHandlerInterface
     */
    public function execute(string $endpoint, array $params): array
    {
        $bodyParams = $this->buildRequestParams($params);

        $this->addCurlOpt(CURLOPT_URL, "{$this->credentials->getHost()}{$endpoint}")
             ->addCurlOpt(CURLOPT_CUSTOMREQUEST, EnumHttpMethods::POST)
             ->addCurlOpt(CURLOPT_ENCODING, '')
             ->addCurlOpt(CURLOPT_MAXREDIRS, 10)
             ->addCurlOpt(CURLOPT_TIMEOUT, 0)
             ->addCurlOpt(CURLOPT_FOLLOWLOCATION, true)
             ->addCurlOpt(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1)
             ->addCurlOpt(CURLOPT_POSTFIELDS, $bodyParams);

        $this->auth($bodyParams);

        return $this->query();
    }

    /**
     * @param array $queryParams
     * @return string|null
     */
    private function buildRequestParams(array $queryParams): ?string
    {
        return json_encode($queryParams);
    }
}
