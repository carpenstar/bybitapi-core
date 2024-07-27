<?php

namespace Carpenstar\ByBitAPI\Core\Auth;

class Credentials
{
    private string $host;

    private string $apiKey = '';

    private string $secret = '';

    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setSecret(string $secret): self
    {
        $this->secret = $secret;
        return $this;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}
