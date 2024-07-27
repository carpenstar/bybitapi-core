<?php

namespace Carpenstar\ByBitAPI\Core\Builders;

use Carpenstar\ByBitAPI\Core\Enums\EnumHttpMethods;
use Carpenstar\ByBitAPI\Core\Exceptions\SDKException;
use Carpenstar\ByBitAPI\Core\Interfaces\IEndpointInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IFabricInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IParametersInterface;

use Carpenstar\ByBitAPI\Core\Request\Curl;
use Carpenstar\ByBitAPI\Core\Request\GetRequest;
use Carpenstar\ByBitAPI\Core\Request\PostRequest;

class CurlBuilder implements IFabricInterface
{
    /**
     * @param string $className
     * @param IParametersInterface|null $data
     * @return IEndpointInterface
     * @throws \Exception
     */
    public static function make(string $method): Curl
    {
        switch ($method) {
            case EnumHttpMethods::GET:
                return new GetRequest();
            case EnumHttpMethods::POST:
                return new PostRequest();
            default:
                throw new SDKException("Wrong http method detected");
        }
    }
}
