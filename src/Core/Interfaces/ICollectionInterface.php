<?php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

use Carpenstar\ByBitAPI\Core\Objects\ResponseEntity;

interface ICollectionInterface
{
    /**
     * @return $this
     */
    public function push(): self;

    /**
     * @return IResponseEntityInterface[]
     */
    public function all(): array;

    public function fetch();

    public function count(): int;
}