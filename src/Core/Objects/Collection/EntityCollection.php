<?php
namespace Carpenstar\ByBitAPI\Core\Objects\Collection;

use Carpenstar\ByBitAPI\Core\Interfaces\IResponseEntityInterface;

class EntityCollection extends AbstractCollection
{
    public function push(?IResponseEntityInterface $item = null): self
    {
        $this->collection[] = $item;
        return $this;
    }

    /**
     * @return ResponseEntity
     */
    public function fetch(): ?IResponseEntityInterface
    {
        $item = current($this->collection);
        next($this->collection);
        return is_bool($item) ? null : $item;
    }
}