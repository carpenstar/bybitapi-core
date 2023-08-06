<?php
namespace Carpenstar\ByBitAPI\Core\Response;

use Carpenstar\ByBitAPI\Core\Enums\EnumOutputMode;
use Carpenstar\ByBitAPI\Core\Exceptions\ApiException;
use Carpenstar\ByBitAPI\Core\Builders\ResponseDtoBuilder;
use Carpenstar\ByBitAPI\Core\Interfaces\ICurlResponseDtoInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseHandlerInterface;
use Carpenstar\ByBitAPI\Core\Objects\Collection\ArrayCollection;
use Carpenstar\ByBitAPI\Core\Objects\Collection\EntityCollection;
use Carpenstar\ByBitAPI\Core\Objects\Collection\StringCollection;

class CurlResponseHandler implements IResponseHandlerInterface
{
    /**
     * @var string $entityClassName
     */
    private string $entity;

    /**
     * @param string $className
     * @return void
     */
    public function bindDto(string $className): self
    {
        $this->entity = $className;
        return $this;
    }

    /**
     * @return IResponseHandlerInterface
     * @throws \Exception
     */
    public function handle(string $apiData, int $mode = EnumOutputMode::MODE_ARRAY): ICurlResponseDtoInterface
    {
        $data = json_decode($apiData, true);

        // If return NULL, then service blocked that ip, try again after 10 minutes
        if (is_null($data)) {
            $data = [
                'time' => time(),
                'retCode' => 400,
                'retMsg' => 'Forbidden, try again later',
                'result' => ['list' =>[]],
                'retExtInfo' => []
            ];
        }

        if (!empty($data["retCode"]) && (int)$data["retCode"] > 0) {
            throw new ApiException($data["retMsg"], $data["retCode"]);
        }

        $time = $data['time'] ?? 0;
        $code = $data['retCode'] ?? 0;
        $msg = $data['retMsg'] ?? '';
        $extMsg = $data['retExtInfo'] ?? [];
        $cursor = $data['result']['nextPageCursor'] ?? '';


        if (isset($data['result'][$this->entity::$rootDataKey])) {
            $data = $data['result'][$this->entity::$rootDataKey];
        } elseif (!empty($data['result'])) {
            $data = [$data['result']];
        } elseif (empty($data['result'])) {
            $data = [];
        }

        switch ($mode) {
            case EnumOutputMode::MODE_JSON:
                $collection = new StringCollection();
                $collection->push($apiData);
                break;
            case EnumOutputMode::MODE_ARRAY:
                $collection = new ArrayCollection();
                array_walk($data, function ($item) use ($collection) {
                    $collection->push($item);
                });
                break;
            case EnumOutputMode::MODE_ENTITY:
            default:
                $collection = new EntityCollection();
                array_walk($data, function ($item) use ($collection) {
                    if (!empty($item)) {
                        $collection->push(ResponseDtoBuilder::make($this->entity, $item));
                    }
                });
                break;
        }

        return (new CurlResponseDto())
            ->setTime($time)
            ->setReturnCode($code)
            ->setReturnMessage($msg)
            ->setReturnExtendedInfo($extMsg)
            ->setNextPageCursor($cursor)
            ->setBody($collection);
    }
}