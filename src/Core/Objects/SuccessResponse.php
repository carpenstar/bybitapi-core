<?php

namespace Carpenstar\ByBitAPI\Core\Objects;

use Carpenstar\ByBitAPI\Core\Builders\ResponseDtoBuilder;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\ISuccessCurlResponseDtoInterface;
use Carpenstar\ByBitAPI\Core\Objects\Collection\EntityCollection;

class SuccessResponse implements ISuccessCurlResponseDtoInterface, IResponseInterface
{
    private int $retCode;
    private string $retMsg;
    private array $retExtInfo;
    private ?string $nextPageCursor;
    private AbstractResponse $result;

    public function __construct(string $responseClassName, int $retCode, string $retMsg, ?array $retExtInfo, array $result, ?string $nextPageCursor = null)
    {
        $this->retCode = $retCode;
        $this->retMsg = $retMsg;
        $this->retExtInfo = $retExtInfo ?? [];
        $this->nextPageCursor = $nextPageCursor;
        $this->result = $this->draw([$result], $responseClassName);
    }

    public function getReturnCode(): int
    {
        return $this->retCode;
    }

    public function getReturnMessage(): string
    {
        return $this->retMsg;
    }

    public function getNextPageCursor(): ?string
    {
        return $this->nextPageCursor;
    }

    public function getResult(): AbstractResponse
    {
        return $this->result;
    }

    protected function draw(array $result, string $responseClassName): AbstractResponse
    {
        $collection = new EntityCollection();
        array_walk($result, function ($item) use ($collection, $responseClassName) {
            $collection->push(ResponseDtoBuilder::make($responseClassName, $item));
        });

        return $collection->fetch();
    }

    public function getExtendedInfo(): array
    {
        return $this->retExtInfo;
    }
}