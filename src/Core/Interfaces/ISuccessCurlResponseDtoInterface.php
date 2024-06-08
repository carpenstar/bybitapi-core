<?php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

use Carpenstar\ByBitAPI\Core\Objects\AbstractResponse;

interface ISuccessCurlResponseDtoInterface
{
    public function getNextPageCursor(): ?string;
}