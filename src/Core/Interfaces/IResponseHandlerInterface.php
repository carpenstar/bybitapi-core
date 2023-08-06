<?php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

interface IResponseHandlerInterface
{
    /**
     * @param string $className
     * @return mixed
     */
    public function bindDto(string $className);
    /**
     * @return IResponseHandlerInterface
     */
    public function handle(string $apiData): ICurlResponseDtoInterface;
}