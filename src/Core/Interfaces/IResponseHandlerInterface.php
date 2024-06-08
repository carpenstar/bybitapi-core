<?php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

interface IResponseHandlerInterface
{
    /**
     * @return IResponseHandlerInterface
     */
    public function build(array $apiData, string $dto): IResponseInterface;
}