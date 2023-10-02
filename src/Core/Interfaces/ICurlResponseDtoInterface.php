<?php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

interface ICurlResponseDtoInterface
{
    public function getTime(): \DateTime;
    public function getReturnCode(): int;
    public function getReturnMessage(): string;
    public function getReturnExtendedInfo(): array;
    public function getNextPageCursor(): ?string;
    public function getBody(): ICollectionInterface;
}