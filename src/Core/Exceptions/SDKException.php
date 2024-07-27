<?php

namespace Carpenstar\ByBitAPI\Core\Exceptions;

class SDKException extends \Exception
{
    public const EXCEPTION_WRONG_PARAMETER_TEXT = "Wrong parameter: %s. Expected: %s";
    public const EXCEPTION_REQUIRED_FIELD_TEXT = "You must specify the following parameters: %s";
    public const EXCEPTION_REQUIRED_SPECIFY_BETWEEN_FIELDS = "One of two parameters must be specified: %s";
}
