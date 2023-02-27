<?php
namespace Carpenstar\ByBitAPI\Core\Objects\Collection;

class StringCollection extends AbstractCollection
{
    /**
     * @param string|null $item
     * @return $this
     */
    public function push(?string $item = null): self
    {
        $this->collection[0] = $item;
        return $this;
    }

    /**
     * @return string
     */
    public function fetch(): ?string
    {
        $item = current($this->collection);
        next($this->collection);
        return is_bool($item) ? null : $item;
    }
}