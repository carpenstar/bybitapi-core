<?php
namespace Carpenstar\ByBitAPI\Core\Response;

use Carpenstar\ByBitAPI\Core\Enums\EnumOutputMode;
use Carpenstar\ByBitAPI\Core\Exceptions\ApiException;
use Carpenstar\ByBitAPI\Core\Builders\ResponseBuilder;
use Carpenstar\ByBitAPI\Core\Helpers\DateTimeHelper;
use Carpenstar\ByBitAPI\Core\Interfaces\ICollectionInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
use Carpenstar\ByBitAPI\Core\Objects\Collection\ArrayCollection;
use Carpenstar\ByBitAPI\Core\Objects\Collection\EntityCollection;
use Carpenstar\ByBitAPI\Core\Objects\Collection\StringCollection;

class CurlResponse implements IResponseInterface
{
    /**
     * @var string $entityClassName
     */
    private string $entity;

    /**
     * @var int $retCode
     */
    private int $retCode;

    /**
     * @var string $retMsg
     */
    private string $retMsg;

    private string $rawResult;

    private array $bodyApiResult = [];

    /**
     * @var ICollectionInterface $result
     */
    private ICollectionInterface $result;

    /**
     * @var array $retExtInfo
     */
    private array $retExtInfo = [];

    /**
     * @var \DateTime $time
     */
    private \DateTime $time;

    public function __construct(string $jsonApiResult)
    {
        $this->setRawResult($jsonApiResult);
        $data = json_decode($jsonApiResult, true);


        if (!empty($data["retCode"]) && (int)$data["retCode"] > 0) {
            throw new ApiException($data["retMsg"], $data["retCode"]);
        }

        $this
            ->setBodyApiResult($data["result"])
            ->setReturnCode($data["retCode"])
            ->setReturnMessage($data["retMsg"])
            ->setReturnExtendedInfo($data["retExtInfo"])
            ->setTime($data["time"]);
    }

    /**
     * @param int $code
     * @return IResponseInterface
     */
    private function setReturnCode(int $code): self
    {
        $this->retCode = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getReturnCode(): int
    {
        return $this->retCode;
    }

    /**
     * @param string $message
     * @return IResponseInterface
     */
    private function setReturnMessage(string $message): self
    {
        $this->retMsg = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getReturnMessage(): string
    {
        return $this->retMsg;
    }

    /**
     * @param string $data
     * @return $this
     */
    private function setRawResult(string $data): self
    {
        $this->rawResult = $data;
        return $this;
    }

    /**
     * @return string
     */
    private function getRawResult(): string
    {
        return $this->rawResult;
    }

    /**
     * @param array $bodyApiResult
     * @return $this
     */
    private function setBodyApiResult(array $bodyApiResult): self
    {
        $this->bodyApiResult = $bodyApiResult;
        return $this;
    }

    /**
     * @return array
     */
    private function getBodyApiResult(): array
    {
        return $this->bodyApiResult;
    }

    /**
     * @param ICollectionInterface $collection
     * @return IResponseInterface
     */
    private function setResult(ICollectionInterface $collection): self
    {
        $this->result = $collection;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getBody(): ICollectionInterface
    {
        return $this->result;
    }

    /**
     * @param array $extInfo
     * @return IResponseInterface
     */
    private function setReturnExtendedInfo(array $extInfo): self
    {
        $this->retExtInfo = $extInfo;
        return $this;
    }

    /**
     * @return array
     */
    public function getReturnExtendedInfo(): array
    {
        return $this->retExtInfo;
    }

    /**
     * @param int $time
     * @return IResponseInterface
     */
    private function setTime(int $time): self
    {
        $this->time = DateTimeHelper::makeFromTimestamp($time);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * @param string $className
     * @return void
     */
    public function bindEntity(string $className): self
    {
        $this->entity = $className;
        return $this;
    }

    /**
     * @return IResponseInterface
     * @throws \Exception
     */
    public function handle(int $outputMode): IResponseInterface
    {
        $data = $this->getBodyApiResult();

        $isResponseHasEmptyList = (empty($data[$this->entity::$rootDataKey]) && !isset($data[$this->entity::$rootDataKey]));

        $data = $isResponseHasEmptyList ? [$this->entity::$rootDataKey => [$data]] : $data;

        switch ($outputMode) {
            case EnumOutputMode::MODE_JSON:
                $collection = new StringCollection();
                $collection->push($this->getRawResult());
                break;
            case EnumOutputMode::MODE_ARRAY:
                $collection = new ArrayCollection();
                array_walk($this->bodyApiResult[$this->entity::$rootDataKey], function ($item) use ($collection) {
                    $collection->push($item);
                });
                break;
            case EnumOutputMode::MODE_ENTITY:
            default:
                $collection = new EntityCollection();
                array_walk($data[$this->entity::$rootDataKey], function ($item) use ($collection) {
                    $collection->push(ResponseBuilder::make($this->entity, $item));
                });
                break;
        }

        return $this->setResult($collection);
    }
}