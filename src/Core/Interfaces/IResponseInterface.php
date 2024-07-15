<?php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

use Carpenstar\ByBitAPI\Core\Objects\AbstractResponse;

interface IResponseInterface
{
    public function getReturnCode(): int;
    public function getReturnMessage(): string;
    public function getExtendedInfo(): array;
    public function getResult(): AbstractResponse;

}