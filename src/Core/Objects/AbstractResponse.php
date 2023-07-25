<?php
namespace Carpenstar\ByBitAPI\Core\Objects;

use Carpenstar\ByBitAPI\Core\Interfaces\IResponseDataInterface;

abstract class AbstractResponse implements IResponseDataInterface
{
    public static string $rootDataKey = 'list';

    abstract public function __construct(array $data);
}