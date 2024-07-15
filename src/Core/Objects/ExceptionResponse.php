<?php

namespace Carpenstar\ByBitAPI\Core\Objects;

use Carpenstar\ByBitAPI\Core\Helpers\DateTimeHelper;
use Carpenstar\ByBitAPI\Core\Interfaces\IExceptionCurlResponseInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
use Carpenstar\ByBitAPI\Core\Objects\Collection\EntityCollection;

class ExceptionResponse implements IExceptionCurlResponseInterface, IResponseInterface
{
    private int $retCode;
    private string $retMsg;
    private array $retExtInfo;
    private EntityCollection $result;
    private \DateTime $time;

    public function __construct(int $retCode, string $retMsg, ?array $retExtInfo, int $time)
    {
        $this->retCode = $retCode;
        $this->retMsg = $retMsg;
        $this->retExtInfo = $retExtInfo ?? [];
        $this->time = DateTimeHelper::makeFromTimestamp($time);
        $this->result = new EntityCollection();
    }

    public function getReturnCode(): int
    {
        return $this->retCode;
    }

    public function getReturnMessage(): string
    {
        return $this->retMsg;
    }

    public function getExtendedInfo(): array
    {
        return $this->retExtInfo;
    }

    public function getResult(): AbstractResponse
    {
        return $this->result->fetch();
    }

    public function getTime()
    {
        return $this->time;
    }
}