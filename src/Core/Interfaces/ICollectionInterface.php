<?php

namespace Carpenstar\ByBitAPI\Core\Interfaces;

use Carpenstar\ByBitAPI\Core\Objects\AbstractResponse;

interface ICollectionInterface
{
    /**
     * @return $this
     */
    public function push(): self;

    /**
     * @return IResponseDataInterface[]
     */
    public function all(): array;

    public function fetch();

    public function count(): int;
}
