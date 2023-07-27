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
    public function exec(string $endpoint, array $queryParams): string
    {
        $queryString = $this->buildRequestParams($queryParams);

        $this->addCurlOpt(CURLOPT_URL, static::$host . $endpoint . '?' . $queryString)
             ->addCurlOpt(CURLOPT_CUSTOMREQUEST, EnumHttpMethods::GET);

        if ($this->isNeedAuthorization) {
            $this->auth($queryString);
        }

        return $this->execute();
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