<?php

namespace Carpenstar\ByBitAPI\Core\Endpoints;

use Carpenstar\ByBitAPI\Core\Interfaces\IPrivateEndpointInterface;

abstract class PrivateEndpoint extends Endpoint implements IPrivateEndpointInterface
{
    public function isNeedAuthorization(): bool
    {
        return true;
    }
}
