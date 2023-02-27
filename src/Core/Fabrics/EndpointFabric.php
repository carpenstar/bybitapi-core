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
        /**
         * @var IEndpointInterface $endpoint
         */
        $endpoint = new $className();
        if (!in_array(IEndpointInterface::class, class_implements($endpoint))) {
            throw new \Exception("The endpoint {$className} must implement the interface " . IEndpointInterface::class . "!");
        }

         /**
         * Проверка необходимости и наличия переданного обьекта параметров запроса
         */
        $isRequestParameterExist = class_exists($className . 'QueryBag');
        if ($isRequestParameterExist && empty($data)) {
            throw new \Exception("The endpoint {$className} required request parameters from {$className}Parameters !");
        }

        if ($isRequestParameterExist && get_class($data) != $className . 'QueryBag') {
            throw new \Exception("The parameters passed must be a class {$className}Parameters instead " . get_class($data));
        }

        return $endpoint
            ->setOutputMode($outputMode)
            ->setParameters($isRequestParameterExist ? $data : new StubQueryBag());
    }
}