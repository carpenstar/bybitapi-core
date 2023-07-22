<?php
namespace Carpenstar\ByBitAPI\Core\Endpoints;

use Carpenstar\ByBitAPI\Core\Enums\EnumHttpMethods;
use Carpenstar\ByBitAPI\Core\Enums\EnumOutputMode;
use Carpenstar\ByBitAPI\Core\Exceptions\ApiException;
use Carpenstar\ByBitAPI\Core\Interfaces\IEndpointInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IOptionsInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
use Carpenstar\ByBitAPI\Core\Objects\ResponseEntity;
use Carpenstar\ByBitAPI\Core\Objects\StubQueryBag;
use Carpenstar\ByBitAPI\Core\Request\Curl;
use Carpenstar\ByBitAPI\Core\Request\GetRequest;
use Carpenstar\ByBitAPI\Core\Request\PostRequest;
use Carpenstar\ByBitAPI\Spot\Trade\PlaceOrder\PlaceOrder;

abstract class Endpoint implements IEndpointInterface
{
    /**
     * @var string HTTP-метод GET, POST и т.д
     */
    protected string $method;
    protected string $url;
    protected int $outputMode;
    protected IOptionsInterface $options;

    abstract protected function getResponseClassname(): string;
    abstract protected function getOptionsClassname(): string;
    abstract protected function getEndpointUrl(): string;

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    public function setOutputMode(int $outputMode): self
    {
        $this->outputMode = $outputMode;
        return $this;
    }

    public function getOutputMode(): int
    {
        return $this->outputMode;
    }

    /**
     * @param IOptionsInterface $options
     * @return $this
     * @throws ApiException
     */
    public function bindRequestOptions(?IOptionsInterface $options): self
    {
        if (get_class($options ?? new StubQueryBag()) != $this->getOptionsClassname()) {
            throw new ApiException(get_class($options) . " must be instance of " . $this->getOptionsClassname());
        }

        $this->options = $options ?? new StubQueryBag();
        return $this;
    }

    /**
     * @return IOptionsInterface
     */
    public function getRequestOptions(): IOptionsInterface
    {
        return $this->options;
    }

    public function execute(): IResponseInterface
    {
        switch (static::HTTP_METHOD) {
            case EnumHttpMethods::GET:
                $request = GetRequest::getInstance(static::IS_NEED_AUTHORIZATION);
                break;
            case EnumHttpMethods::POST:
                $request = PostRequest::getInstance(static::IS_NEED_AUTHORIZATION);
                break;
            default:
                throw new \Exception("Http Method not detected");
        }

        $response = $request
            ->exec($this->getEndpointUrl(), $this->getRequestOptions()->fetchArray())
            ->bindEntity(static::getResponseClassname());

        return $response->handle($this->getOutputMode());
    }
}