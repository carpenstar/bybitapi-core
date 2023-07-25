<?php
namespace Carpenstar\ByBitAPI\Core\Builders;

use Carpenstar\ByBitAPI\Core\Interfaces\IFabricInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseDataInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;

class ResponseBuilder implements IFabricInterface
{
    /**
     * @param string $className
     * @param array|null $data
     * @return IResponseDataInterface
     * @throws \Exception
     */
    public static function make(string $className, ?array $data = null): IResponseDataInterface
    {
        if (!in_array(IResponseDataInterface::class, class_implements($className))) {
            throw new \Exception("The endpoint {$className} must implement the interface " . IResponseInterface::class . "!");
        }

        return new $className($data);
    }
}