<?php

namespace Carpenstar\ByBitAPI\Core\Objects\WebSockets;

use Carpenstar\ByBitAPI\Core\Auth\Credentials;
use Carpenstar\ByBitAPI\Core\Objects\WebSockets\Entity\WebSocketConnectionResponse;
use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;
use Carpenstar\ByBitAPI\Core\Builders\ResponseDtoBuilder;
use Carpenstar\ByBitAPI\Core\Interfaces\IResponseDataInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IChannelHandlerInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IWebSocketArgumentInterface;
use Carpenstar\ByBitAPI\Core\Interfaces\IWebSocketsChannelInterface;

abstract class WebSocketsPublicChannel implements IWebSocketsChannelInterface
{
    public const CHANNEL_ACCESS = 'public';
    protected string $hostStream;
    protected string $wsRoute;
    protected array $topic;
    protected string $operation;
    protected Worker $worker;
    protected IChannelHandlerInterface $callback;
    protected IResponseDataInterface $response;

    public function __construct(
        IWebSocketArgumentInterface $argument,
        Credentials $credentials,
        IChannelHandlerInterface $callback
    ) {
        $this->worker = new Worker();
        $this->topic = $argument->getTopic();
        $this->operation = $this->getOperation();
        $this->hostStream = $credentials->getHost();
        $this->callback = $callback;
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $this->worker->onWorkerStart = function () {

            $connection = new AsyncTcpConnection($this->hostStream . static::CHANNEL_TYPE . "/" . static::CHANNEL_ACCESS . "/v3");

            $connection->transport = 'ssl';
            $connection->onConnect = function ($connection) {
                $connection->send(json_encode(["op" => $this->operation, "args" => $this->topic]));
            };

            $callback = $this->callback;

            $connection->onMessage = function ($connection, $message) use ($callback) {
            
                $message = json_decode($message, true);

                $responseDto = (empty($message['op'])) ? $this->getResponseClassname() : WebSocketConnectionResponse::class; 

                $dtoMessage = ResponseDtoBuilder::make($responseDto, $message);
                $callback->handle($dtoMessage, $connection);
            };
            
            $connection->connect();
        };

        Worker::runAll();
    }
}
