<?php

namespace Carpenstar\ByBitAPI\Core\Endpoints;

use Carpenstar\ByBitAPI\Core\Interfaces\IPublicEndpointInterface;

abstract class PublicEndpoint extends Endpoint implements IPublicEndpointInterface
{
    public function isNeedAuthorization(): bool
    {
        return false;
    }
}
