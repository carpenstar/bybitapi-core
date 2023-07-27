<?php
namespace Carpenstar\ByBitAPI\Core\Builders;

use Carpenstar\ByBitAPI\Core\Enums\EnumOutputMode;
use Carpenstar\ByBitAPI\Core\Interfaces\ICurlResponseDtoInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IFabricInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseDataInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseHandlerInterface;

class ResponseHandlerBuilder implements IFabricInterface
{
    /**
     * @param string $data
     * @param string|null $curlResponse
     * @param string|null $dto
     * @param int $resultMode
     * @return ICurlResponseDtoInterface
     * @throws \Exception
     */
    public static function make(string $data, string $curlResponse = null, string $dto = null, int $mode = EnumOutputMode::DEFAULT_MODE): ICurlResponseDtoInterface
    {
        if (!in_array(IResponseHandlerInterface::class, class_implements($curlResponse))) {
            throw new \Exception("{$dto} must be implements the interface " . IResponseHandlerInterface::class . "!");
        }

        if (!in_array(IResponseDataInterface::class, class_implements($dto))) {
            throw new \Exception("{$dto} must be implements the interface " . IResponseDataInterface::class . "!");
        }

        /**
         * @var IResponseHandlerInterface $curlResponse
         */
        $curlResponse = new $curlResponse();

        return $curlResponse->bindEntity($dto)->handle($data, $mode);
    }
}