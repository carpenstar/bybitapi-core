<?php

namespace Carpenstar\ByBitAPI\Core\Request;

use Carpenstar\ByBitAPI\Core\Enums\EnumHttpMethods;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseHandlerInterface;

class GetRequest extends Curl
{
    /**
     * @param string $endpoint
     * @param array $queryParams
     * @return IResponseHandlerInterface
     */
    public function execute(string $endpoint, array $queryParams): array
    {
        $queryString = $this->buildRequestParams($queryParams);

        $this->addCurlOpt(CURLOPT_URL, $this->credentials->getHost() . $endpoint . '?' . $queryString)
             ->addCurlOpt(CURLOPT_CUSTOMREQUEST, EnumHttpMethods::GET);

        $this->auth($queryString);


        return $this->query();
    }

    /**
     * @param array $queryParams
     * @return string
     */
    private function buildRequestParams(array $queryParams): ?string
    {
        return http_build_query($queryParams);
    }
}
