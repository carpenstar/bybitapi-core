<?php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

interface IResponseHandlerInterface
{
    /**
     * @param string $className
     * @return mixed
     */
    public function bindEntity(string $className);
    /**
     * @return IResponseHandlerInterface
     */
    public function handle(string $apiData): ICurlResponseDtoInterface;
}