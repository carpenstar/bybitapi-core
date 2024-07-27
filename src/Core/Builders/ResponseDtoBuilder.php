<?php

namespace Carpenstar\ByBitAPI\Core\Builders;

use Carpenstar\ByBitAPI\Core\Interfaces\IFabricInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseDataInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseHandlerInterface;

class ResponseDtoBuilder implements IFabricInterface
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
            throw new \Exception("That DTO {$className} must be implements the interface " . IResponseHandlerInterface::class . "!");
        }

        return new $className($data);
    }
}
