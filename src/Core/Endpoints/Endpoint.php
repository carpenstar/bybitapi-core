<?php
namespace Carpenstar\ByBitAPI\Core\Endpoints;

use Carpenstar\ByBitAPI\Core\Builders\ResponseHandlerBuilder;
use Carpenstar\ByBitAPI\Core\Enums\EnumHttpMethods;
use Carpenstar\ByBitAPI\Core\Exceptions\ApiException;
use Carpenstar\ByBitAPI\Core\Interfaces\ICurlResponseDtoInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IEndpointInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IParametersInterface;
use Carpenstar\ByBitAPI\Core\Objects\StubQueryBag;
use Carpenstar\ByBitAPI\Core\Request\Curl;
use Carpenstar\ByBitAPI\Core\Request\GetRequest;
use Carpenstar\ByBitAPI\Core\Request\PostRequest;
use Carpenstar\ByBitAPI\Core\Response\CurlResponseHandler;

abstract class Endpoint implements IEndpointInterface
{
    /**
     * @var string HTTP-метод GET, POST и т.д
     */
    protected string $method;
    protected string $url;
    protected IParametersInterface $parameters;

    abstract protected function getResponseClassname(): string;
    abstract protected function getRequestClassname(): string;
    abstract protected function getEndpointUrl(): string;

    /**
     * @return string
     */
    protected function getResponseHandlerClassname(): string
    {
        return CurlResponseHandler::class;
    }

    /**
     * @param IParametersInterface|null $requestParameters
     * @return $this
     * @throws ApiException
     */
    public function bindRequestParameters(?IParametersInterface $requestParameters): self
    {
        if (get_class($requestParameters ?? new StubQueryBag()) != $this->getRequestClassname()) {
            throw new ApiException(get_class($requestParameters) . " must be instance of " . $this->getRequestClassname());
        }

        $this->parameters = $requestParameters ?? new StubQueryBag();
        return $this;
    }

    /**
     * @return Curl
     * @throws \Exception
     */
    protected function makeRequestObject(): Curl
    {
        switch (static::HTTP_METHOD) {
            case EnumHttpMethods::GET:
                return GetRequest::getInstance(static::IS_NEED_AUTHORIZATION);
            case EnumHttpMethods::POST:
                return PostRequest::getInstance(static::IS_NEED_AUTHORIZATION);
            default:
                throw new \Exception("Http Method not detected");
        }
    }

    /**
     * @param int $mode
     * @return ICurlResponseDtoInterface
     * @throws \Exception
     */
    public function execute(int $mode): ICurlResponseDtoInterface
    {
        return ResponseHandlerBuilder::make(
            $this->makeRequestObject()->exec(
                $this->getEndpointUrl(), $this->parameters->array()
            ),
            $this->getResponseHandlerClassname(),
            $this->getResponseClassname(),
            $mode
        );
    }
}