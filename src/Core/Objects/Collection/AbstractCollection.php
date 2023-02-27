<?php
namespace Carpenstar\ByBitAPI\Core\Objects\Collection;

use Carpenstar\ByBitAPI\Core\Interfaces\ICollectionInterface;

abstract class AbstractCollection implements ICollectionInterface
{
    /**
     * @var array $collection
     */
    protected array $collection = [];

    abstract public function push(): self;
    abstract public function fetch();

    public function all(): array
    {
        return $this->collection;
    }

    public function count(): int
    {
        return count($this->collection);
    }
}