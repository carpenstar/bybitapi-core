<?php
namespace Carpenstar\ByBitAPI\Core\Traits;

use Carpenstar\ByBitAPI\Core\Builders\ResponseHandlerBuilder;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\ISuccessCurlResponseDtoInterface;

trait OverrideExecuteTrait
{
    /**
     * @param int $mode
     * @param string|null $jsonData
     * @return ISuccessCurlResponseDtoInterface
     * @throws \Exception
     */
    public function execute(int $mode, string $jsonData = null): IResponseInterface
    {
        return ResponseHandlerBuilder::make(
            $jsonData,
            $this->getResponseHandlerClassname(),
            $this->getResponseClassname(),
            $mode
        );
    }
}