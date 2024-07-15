<?php
namespace Carpenstar\ByBitAPI\Core\Endpoints;

use Carpenstar\ByBitAPI\Core\Enums\EnumHttpMethods;
use Carpenstar\ByBitAPI\Core\Exceptions\ApiException;
use Carpenstar\ByBitAPI\Core\Exceptions\SDKException;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IEndpointInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IParametersInterface;
use Carpenstar\ByBitAPI\Core\Objects\StubQueryBag;
use Carpenstar\ByBitAPI\Core\Request\GetRequest;
use Carpenstar\ByBitAPI\Core\Request\PostRequest;
use Carpenstar\ByBitAPI\Core\Response\CurlResponseHandler;

abstract class Endpoint implements IEndpointInterface
{
    protected string $method;
    protected string $url;
    protected IParametersInterface $parameters;
    abstract protected function getResponseClassnameByCondition(array &$apiData = null): string;
    abstract protected function getRequestClassname(): string;
    abstract protected function getEndpointUrl(): string;

    /**
     * @param IParametersInterface|null $requestParameters
     * @return $this
     * @throws ApiException|SDKException
     */
    public function bindRequestParameters(?IParametersInterface $requestParameters): self
    {
        if (get_class($requestParameters ?? new StubQueryBag()) != $this->getRequestClassname()) {
            throw new SDKException(get_class($requestParameters) . " must be instance of " . $this->getRequestClassname());
        }

        $this->parameters = $requestParameters ?? new StubQueryBag();
        return $this;
    }

    /**
     * @return IResponseInterface
     * @throws \Exception
     */
    public function execute(): IResponseInterface
    {
        switch (static::HTTP_METHOD) {
            case EnumHttpMethods::GET:
                $curl = GetRequest::getInstance(static::IS_NEED_AUTHORIZATION);
                break;
            case EnumHttpMethods::POST:
                $curl = PostRequest::getInstance(static::IS_NEED_AUTHORIZATION);
                break;
            default:
                throw new \Exception("Http Method not detected");
        }

        $apiData = $curl->exec($this->getEndpointUrl(), $this->parameters->array());


        return (new CurlResponseHandler())->build($apiData,  $this->getResponseClassnameByCondition($apiData));
    }
}