<?php

namespace Carpenstar\ByBitAPI;

use Carpenstar\ByBitAPI\Core\Auth\Credentials;
use Carpenstar\ByBitAPI\Core\Enums\EnumOutputMode;
use Carpenstar\ByBitAPI\Core\Exceptions\SDKException;
use Carpenstar\ByBitAPI\Core\Builders\RestBuilder;
use Carpenstar\ByBitAPI\Core\Interfaces\IEndpointInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IParametersInterface;
use Carpenstar\ByBitAPI\Core\Objects\AbstractParameters;
use Carpenstar\ByBitAPI\Core\Request\Curl;
use Carpenstar\ByBitAPI\WebSockets\Builders\WebSocketsBuilder;
use Carpenstar\ByBitAPI\WebSockets\Interfaces\IChannelHandlerInterface;
use Carpenstar\ByBitAPI\WebSockets\Interfaces\IWebSocketArgumentInterface;

class BybitAPI
{
    protected Credentials $credentials;

    /**
     * @param string $host
     * @param string $apiKey
     * @param string $secret
     * @return $this
     */
    public function setCredentials(string $host, string $apiKey = '', string $secret = ''): self
    {
        $this->credentials = (new Credentials())
            ->setHost($host)
            ->setApiKey($apiKey)
            ->setSecret($secret);

        return $this;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost(string $host): self
    {
        $this->credentials->setHost($host);
        return $this;
    }

    /**
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey(string $apiKey): self
    {
        $this->setApiKey($apiKey);
        return $this;
    }

    /**
     * @param string $secret
     * @return $this
     */
    public function setSecret(string $secret): self
    {
        $this->credentials->setSecret($secret);
        return $this;
    }

    /**
     * @param string $endpointClassName
     * @param IParametersInterface|null $parameters
     * @return IResponseInterface
     * @throws SDKException
     */
    public function publicEndpoint(string $endpointClassName, ?IParametersInterface $parameters = null): IEndpointInterface
    {
        if (empty($this->credentials->getHost())) {
            throw new SDKException("Host must be specified");
        }

        return $this->endpoint($endpointClassName, $parameters);
    }

    /**
     * @param string $endpointClassName
     * @param IParametersInterface|null $parameters
     * @return IResponseInterface
     * @throws SDKException
     */
    public function privateEndpoint(string $endpointClassName, ?IParametersInterface $parameters = null): IEndpointInterface
    {
        if (empty($this->credentials->getHost())) {
            throw new SDKException("Host must be specified");
        }
        if (empty($this->credentials->getApiKey())) {
            throw new SDKException("Api key must be specified");
        }
        if (empty($this->credentials->getSecret())) {
            throw new SDKException("Client secret must be specified");
        }

        return $this->endpoint($endpointClassName, $parameters);
    }

    /**
     *
     * @param string $endpointClassName
     * @param AbstractParameters $params
     * @return IEndpointInterface
     * @throws \Exception
     */
    private function endpoint(string $endpointClassName, AbstractParameters $parameters = null): IEndpointInterface
    {
        return RestBuilder::make($endpointClassName, $this->credentials, $parameters);
    }

    /**
     * Soon at v.5.1.0.0
     * @param string $webSocketChannelClassName
     * @param array $data
     * @return void
     * @throws \Exception
     */
    private function websocket(string $webSocketChannelClassName, IWebSocketArgumentInterface $data, IChannelHandlerInterface $channelHandler, int $mode = EnumOutputMode::MODE_ENTITY, int $wsClientTimeout = IWebSocketArgumentInterface::DEFAULT_SOCKET_CLIENT_TIMEOUT): void
    {
        WebSocketsBuilder::make($webSocketChannelClassName, $data, $channelHandler, $mode, $wsClientTimeout)->execute();
    }
}
