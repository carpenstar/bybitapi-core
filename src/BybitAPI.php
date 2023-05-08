<?php
namespace Carpenstar\ByBitAPI;

use Carpenstar\ByBitAPI\Core\Auth\Credentials;
use Carpenstar\ByBitAPI\Core\Enums\EnumOutputMode;
use Carpenstar\ByBitAPI\Core\Exceptions\ApiException;
use Carpenstar\ByBitAPI\Core\Exceptions\SDKException;
use Carpenstar\ByBitAPI\Core\Builders\RestBuilder;
use Carpenstar\ByBitAPI\Core\Interfaces\IRequestInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface;
use Carpenstar\ByBitAPI\WebSockets\Builders\WebSocketsBuilder;
use Carpenstar\ByBitAPI\WebSockets\Interfaces\IChannelHandlerInterface;
use Carpenstar\ByBitAPI\WebSockets\Interfaces\IWebSocketArgumentInterface;

class BybitAPI
{
    /**
     * @param string $host
     * @param string $apiKey
     * @param string $secret
     */
    public function __construct(string $host, string $apiKey, string $secret)
    {
        Credentials::setHost($host);
        Credentials::setApiKey($apiKey);
        Credentials::setSecret($secret);
    }

    /**
     * @param string $endpointClassName
     * @param IRequestInterface|null $parameters
     * @return IResponseInterface
     * @throws \Exception
     */
    public function rest(string $endpointClassName, ?IRequestInterface $parameters = null, ?int $outputMode = EnumOutputMode::DEFAULT_MODE): IResponseInterface
    {
        try {
            return RestBuilder::make($endpointClassName, $parameters, $outputMode)->execute();
        } catch (ApiException $e) {
            $this->exception($e);
        } catch (\Exception $e) {
            $this->exception($e);
            throw new SDKException($e);
        }
    }

    /**
     * @param string $webSocketChannelClassName
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function websocket(string $webSocketChannelClassName, IWebSocketArgumentInterface $data, IChannelHandlerInterface $channelHandler, int $mode = EnumOutputMode::MODE_ENTITY, int $wsClientTimeout = IWebSocketArgumentInterface::DEFAULT_SOCKET_CLIENT_TIMEOUT): void
    {
        WebSocketsBuilder::make($webSocketChannelClassName, $data, $channelHandler, $mode, $wsClientTimeout)->execute();
    }

    /**
     * Method for custom redeclare exceptions
     * @param \Exception $e
     * @return void
     */
    protected function exception(\Exception $e)
    {
        echo json_encode([
            'code' => $e->getCode(),
            'message' => $e->getMessage()
        ]);
        die;
    }
}

