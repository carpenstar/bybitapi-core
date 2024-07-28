<?php

namespace Carpenstar\ByBitAPI\Core\Objects\Collection;

use Carpenstar\ByBitAPI\Core\Interfaces\IResponseDataInterface;

class EntityCollection extends AbstractCollection
{
    public function push(?IResponseDataInterface $item = null): self
    {
        $this->collection[] = $item;
        return $this;
    }

    /**
     * @return array|IResponseDataInterface
     */
    public function fetch(): array|IResponseDataInterface
    {
        $item = current($this->collection);
        next($this->collection);
        return is_bool($item) ? [] : $item;
    }
}
