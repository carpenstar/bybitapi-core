<?php
namespace Carpenstar\ByBitAPI\Core\Endpoints;

use Carpenstar\ByBitAPI\Core\Enums\EnumHttpMethods;
use Carpenstar\ByBitAPI\Core\Enums\EnumOutputMode;
use Carpenstar\ByBitAPI\Core\Exceptions\ApiException;
use Carpenstar\ByBitAPI\Core\Interfaces\IEndpointInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IParametersInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
use Carpenstar\ByBitAPI\Core\Objects\AbstractResponse;
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
    protected int $resultMode;
    protected IParametersInterface $requestParameters;

    protected $response;

    abstract protected function getResponseClassname(): string;
    abstract protected function getRequestClassname(): string;
    abstract protected function getEndpointUrl(): string;

    public function setResultMode(int $resultMode): self
    {
        $this->resultMode = $resultMode;
        return $this;
    }

    /**
     * @param IParametersInterface $options
     * @return $this
     * @throws ApiException
     */
    public function bindRequestParameters(?IParametersInterface $requestParameters): self
    {
        if (get_class($requestParameters ?? new StubQueryBag()) != $this->getRequestClassname()) {
            throw new ApiException(get_class($requestParameters) . " must be instance of " . $this->getRequestClassname());
        }

        $this->requestParameters = $requestParameters ?? new StubQueryBag();
        return $this;
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
            ->exec(
                $this->getEndpointUrl(),
                $this->requestParameters->fetchArray()
            )->bindEntity(
                static::getResponseClassname()
            );

        return $response->handle($this->resultMode);
    }
}