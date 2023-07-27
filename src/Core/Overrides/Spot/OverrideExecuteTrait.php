<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Core\Builders\ResponseHandlerBuilder;
use Carpenstar\ByBitAPI\Core\Interfaces\ICurlResponseDtoInterface;

trait OverrideExecuteTrait
{
    /**
     * @param int $mode
     * @param string|null $jsonData
     * @return ICurlResponseDtoInterface
     * @throws \Exception
     */
    public function execute(int $mode, string $jsonData = null): ICurlResponseDtoInterface
    {
        return ResponseHandlerBuilder::make(
            $jsonData,
            $this->getResponseHandlerClassname(),
            $this->getResponseClassname(),
            $mode
        );
    }
}