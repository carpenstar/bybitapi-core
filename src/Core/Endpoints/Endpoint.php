<?php

namespace Carpenstar\ByBitAPI\Core\Endpoints;

use Carpenstar\ByBitAPI\Core\Auth\Credentials;
use Carpenstar\ByBitAPI\Core\Builders\CurlBuilder;
use Carpenstar\ByBitAPI\Core\Exceptions\ApiException;
use Carpenstar\ByBitAPI\Core\Exceptions\SDKException;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IEndpointInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IParametersInterface;
use Carpenstar\ByBitAPI\Core\Objects\StubQueryBag;
use Carpenstar\ByBitAPI\Core\Response\CurlResponseHandler;

abstract class Endpoint implements IEndpointInterface
{
    protected Credentials $credentials;
    protected string $method;
    protected string $url;
    protected IParametersInterface $parameters;
    abstract protected function getResponseClassnameByCondition(array &$apiData = null): string;
    abstract protected function getRequestClassname(): string;
    abstract protected function getEndpointUrl(): string;

    public function setCredentials(Credentials $credentials): self
    {
        $this->credentials = $credentials;
        return $this;
    }

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
        $reguestParameters = $this->parameters->array();

        $curlInstance = CurlBuilder::make($this->getEndpointRequestMethod())
            ->init($this->isNeedAuthorization(), $this->credentials);

        $data = $curlInstance->execute($this->getEndpointUrl(), $reguestParameters);

        return (new CurlResponseHandler())->build($data, $this->getResponseClassnameByCondition($data));
    }
}
