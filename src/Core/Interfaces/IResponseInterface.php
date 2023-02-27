<?php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

use Carpenstar\ByBitAPI\Core\Objects\Collection\EntityCollection;

interface IResponseInterface
{
    /**
     * @return int
     */
    public function getReturnCode(): int;

    /**
     * @return string
     */
    public function getReturnMessage(): string;

    public function getBody(): ICollectionInterface;

    /**
     * @return array
     */
    public function getReturnExtendedInfo(): array;

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime;

    /**
     * @param string $className
     * @return mixed
     */
    public function bindEntity(string $className);

    /**
     * @return IResponseInterface
     */
    public function handle(int $outputMode): IResponseInterface;

}