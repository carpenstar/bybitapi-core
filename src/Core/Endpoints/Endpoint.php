<?php
namespace Carpenstar\ByBitAPI\Core\Endpoints;

use Carpenstar\ByBitAPI\Core\Enums\EnumHttpMethods;
use Carpenstar\ByBitAPI\Core\Enums\EnumOutputMode;
use Carpenstar\ByBitAPI\Core\Interfaces\IEndpointInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IRequestInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
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
    protected IRequestInterface $parameters;

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
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
     * @param string $url
     * @return Endpoint
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    protected function getResponseEntityClassName(): string
    {
        return get_class($this) . "Response";
    }

    /**
     * @param IRequestInterface $parameters
     * @return $this
     */
    public function setParameters(IRequestInterface $parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return IRequestInterface
     */
    public function getParameters(): IRequestInterface
    {
        return $this->parameters;
    }

    public function execute(): IResponseInterface
    {
        $params = $this->getParameters()->fetchArray();
        switch (static::HTTP_METHOD) {
            case EnumHttpMethods::GET:
                $response = GetRequest::getInstance(static::IS_NEED_AUTHORIZATION)->exec($this->getUrl(), $params);
                break;
            case EnumHttpMethods::POST:
                $response = PostRequest::getInstance(static::IS_NEED_AUTHORIZATION)->exec($this->getUrl(), $params);
                break;
            default:
                throw new \Exception("Http Method not detected");
        }

        return $response->bindEntity(static::getResponseEntityClassName())->handle($this->getOutputMode());
    }

    public function getQueryBagClassName(): string
    {
        return get_class($this) . 'QueryBag';
    }
}