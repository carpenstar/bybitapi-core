<?php
namespace Carpenstar\ByBitAPI\Core\Enums;

interface EnumTimeInForce
{
    /**
     * Good till cancelled (GTC):
     * The order will remain valid until it is fully executed or manually cancelled by the trader.
     * GTC is suitable for traders who are willing to wait for all contracts to be completed at a specified price
     * and can flexibly cancel unconcluded contracts at any time.
     *
     * Ордер будет оставаться в силе до тех пор, пока он не будет полностью исполнен или вручную отменен трейдером.
     * GTC подходит для трейдеров, которые готовы дождаться завершения всех контрактов по указанной цене и
     * могут гибко отменить незаключенные контракты в любое время.
     */
    const GOOD_TILL_CANCELED = "GTC";

    /**
     * Fill or Kill (FOK):
     * The order must be immediately executed at the order price or better, otherwise, it will be completely cancelled
     * and partially filled contracts will not be allowed. This execution strategy is more commonly used by scalping
     * traders or day traders looking for short-term market opportunities.
     *
     * Ордер должен быть немедленно исполнен по цене ордера или выше, в противном случае он будет полностью отменен,
     * а частично исполненные контракты не будут разрешены.
     * Эта стратегия исполнения чаще используется скальпирующими трейдерами или внутридневными трейдерами,
     * ищущими краткосрочные рыночные возможности.
     */
    const FILL_OR_KILL = "FOK";

    /**
     * Immediate or Cancel (IOC):
     * The order must be filled immediately at the order limit price or better.
     * If the order cannot be filled immediately, the unfilled contracts will be cancelled.
     * IOC is usually used to avoid large orders being executed at a price that deviates from the ideal price.
     * With this set, the contracts that fail to trade at the specified price will be cancelled.
     *
     * Ордер должен быть исполнен немедленно по предельной цене ордера или выше.
     * Если ордер не может быть выполнен немедленно, незаполненные контракты будут аннулированы.
     * IOC обычно используется, чтобы избежать исполнения крупных ордеров по цене, которая отличается от идеальной.
     * С этим набором контракты, которые не торгуются по указанной цене, будут отменены.
     */
    const IMMEDIATE_OR_CANCEL = "IOC";
}