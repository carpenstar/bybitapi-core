<?php
namespace Carpenstar\ByBitAPI\Core\Builders;

use Carpenstar\ByBitAPI\Core\Endpoints\Endpoint;
use Carpenstar\ByBitAPI\Core\Enums\EnumOutputMode;
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
    public static function make(string $className, ?IParametersInterface $options = null, ?int $resultMode = EnumOutputMode::DEFAULT_MODE): IEndpointInterface
    {
        if (!in_array(IEndpointInterface::class, class_implements($className))) {
            throw new \Exception("The endpoint {$className} must be implement the interface " . IEndpointInterface::class . "!");
        }

        /** @var Endpoint $endpoint */
        $endpoint = new $className();

        return $endpoint
            ->setResultMode($resultMode)
            ->bindRequestParameters($options);
    }
}