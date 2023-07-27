<?php
namespace Carpenstar\ByBitAPI\Core\Overrides\Spot;

use Carpenstar\ByBitAPI\Spot\Account\WalletBalance\WalletBalance;

class TestWalletBalance extends WalletBalance
{
    use OverrideExecuteTrait;
}