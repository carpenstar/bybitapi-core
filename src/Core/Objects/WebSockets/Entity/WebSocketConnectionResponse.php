<?php
namespace Carpenstar\ByBitAPI\Core\Objects\WebSockets\Entity;

use Carpenstar\ByBitAPI\Core\Objects\AbstractResponse;

class WebSocketConnectionResponse extends AbstractResponse
{
    private bool $success;
    private ?string $returnMessage;
    private string $connectionId;
    private ?string $reqId;
    private string $operation;

    public function __construct(array $data)
    {
        $this->success = $data['success'];
        $this->returnMessage = $data['retm_msg'] ?? null;
        $this->connectionId = $data['conn_id'];
        $this->reqId = $data['req_id'] ?? null;
        $this->operation = $data['op'];
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getReturnMessage(): ?string
    {
        return $this->returnMessage;
    }

    public function getConnectionId(): string
    {
        return $this->connectionId;
    }

    public function getReqId(): ?string 
    {
        return $this->reqId;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }
}
