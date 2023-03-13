<?php
namespace Carpenstar\ByBitAPI\Core\Fabrics;

use Carpenstar\ByBitAPI\Core\Enums\EnumOutputMode;
use Carpenstar\ByBitAPI\Core\Interfaces\IEndpointInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IFabricInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IRequestInterface;
use Carpenstar\ByBitAPI\Core\Objects\StubQueryBag;

class EndpointFabric implements IFabricInterface
{
    /**
     * @param string $className
     * @param IRequestInterface|null $data
     * @return IEndpointInterface
     * @throws \Exception
     */
    public static function make(string $className, ?IRequestInterface $data = null, ?int $outputMode = EnumOutputMode::DEFAULT_MODE): IEndpointInterface
    {
        if (!in_array(IEndpointInterface::class, class_implements($className))) {
            throw new \Exception("The endpoint {$className} must implement the interface " . IEndpointInterface::class . "!");
        }

        $endpoint = new $className();
        $isQueryBagExist = class_exists($endpoint->getQueryBagClassName());
         /**
         * Проверка необходимости и наличия переданного обьекта параметров запроса
         */
        if ($isQueryBagExist && empty($data)) {
            throw new \Exception("The endpoint {$className} required {$endpoint->getQueryBagClassName()} object!");
        }

        return $endpoint
            ->setOutputMode($outputMode)
            ->setParameters($isQueryBagExist ? $data : new StubQueryBag());
    }
}