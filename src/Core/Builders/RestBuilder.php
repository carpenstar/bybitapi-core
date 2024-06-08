<?php
namespace Carpenstar\ByBitAPI\Core\Builders;

use Carpenstar\ByBitAPI\Core\Interfaces\IEndpointInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IFabricInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IParametersInterface;

class RestBuilder implements IFabricInterface
{
    /**
     * @param string $className
     * @param IParametersInterface|null $data
     * @return IEndpointInterface
     * @throws \Exception
     */
    public static function make(string $className, ?IParametersInterface $parameters = null): IEndpointInterface
    {
        if (!in_array(IEndpointInterface::class, class_implements($className))) {
            throw new \Exception("That endpoint {$className} must be implement the interface " . IEndpointInterface::class . "!");
        }

        return (new $className())->bindRequestParameters($parameters);
    }
}