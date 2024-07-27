<?php

namespace Carpenstar\ByBitAPI\Core\Interfaces;

use Carpenstar\ByBitAPI\Core\Auth\Credentials;

interface IEndpointInterface
{
    public function isNeedAuthorization(): bool;
    public function getEndpointRequestMethod(): string;
    public function setCredentials(Credentials $credentials): self;
    public function execute(): IResponseInterface;
}
