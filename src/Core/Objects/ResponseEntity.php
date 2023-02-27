<?php
namespace Carpenstar\ByBitAPI\Core\Objects;

use Carpenstar\ByBitAPI\Core\Interfaces\IResponseEntityInterface;

abstract class ResponseEntity implements IResponseEntityInterface
{
    public static string $rootDataKey = 'list';

    abstract public function __construct(array $data);
}