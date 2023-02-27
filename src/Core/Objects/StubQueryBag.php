<?php
namespace Carpenstar\ByBitAPI\Core\Objects;

class StubQueryBag extends RequestEntity
{
    public function fetchArray(): array
    {
        return [];
    }
}