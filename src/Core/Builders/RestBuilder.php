<?php

namespace Carpenstar\ByBitAPI\Core\Builders;

use Carpenstar\ByBitAPI\Core\Auth\Credentials;
use Carpenstar\ByBitAPI\Core\Interfaces\IEndpointInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IParametersInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IRestFabricInterface;

class RestBuilder implements IRestFabricInterface
{
    /**
     * @param string $className
     * @param IParametersInterface|null $data
     * @return IEndpointInterface
     * @throws \Exception
     */
    public static function make(string $className, Credentials $credentials, ?IParametersInterface $parameters = null): IEndpointInterface
    {
        if (!in_array(IEndpointInterface::class, class_implements($className))) {
            throw new \Exception("That endpoint {$className} must be implement the interface " . IEndpointInterface::class . "!");
        }

        return (new $className())
            ->setCredentials($credentials)
            ->bindRequestParameters($parameters);
    }
}
