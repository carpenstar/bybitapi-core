<?php

namespace Carpenstar\ByBitAPI\Core\Objects\Collection;

use Carpenstar\ByBitAPI\Core\Interfaces\IResponseDataInterface;

class ArrayCollection extends AbstractCollection
{
    public function push(?array $item = null): self
    {
        $this->collection[] = $item;
        return $this;
    }

    /**
     * @return ResponseEntity
     */
    public function fetch(): ?array
    {
        $item = current($this->collection);
        next($this->collection);
        return is_bool($item) ? null : $item;
    }
}
