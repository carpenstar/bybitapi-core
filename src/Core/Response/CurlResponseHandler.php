<?php
namespace Carpenstar\ByBitAPI\Core\Response;


use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseHandlerInterface;
use Carpenstar\ByBitAPI\Core\Objects\ExceptionResponse;
use Carpenstar\ByBitAPI\Core\Objects\SuccessResponse;

class CurlResponseHandler implements IResponseHandlerInterface
{
    /**
     * @param array $apiData
     * @param string $dto
     * @return IResponseInterface
     */
    public function build(array $apiData, string $dto): IResponseInterface
    {
        if ($apiData['retCode'] == 0) {
            return new SuccessResponse($dto, $apiData['retCode'], $apiData['retMsg'], $apiData['retExtInfo'], $apiData['result'], $apiData['nextPageCursor'] ?? '');
        } else {
            return new ExceptionResponse($apiData['retCode'], $apiData['retMsg'], $apiData['retExtInfo'], $apiData['time']);
        }
    }
}
