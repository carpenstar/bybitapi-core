[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/badges/build.png?b=master)](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

# Bybit SDK 

## Available packages


[SPOT-trading package](https://github.com/carpenstar/bybitapi-sdk-spot)
```sh 
composer require carpenstar/bybitapi-sdk-spot
```


[DERIVATIVES-trading package](https://github.com/carpenstar/bybitapi-sdk-derivatives)
```sh 
composer require carpenstar/bybitapi-sdk-derivatives
```


[WEBSOCKETS channels](https://github.com/carpenstar/bybitapi-sdk-websockets)
```sh 
composer require carpenstar/bybitapi-sdk-websockets
```


## Getting started

```php
use Carpenstar\ByBitAPI\BybitAPI;

$api = new BybitAPI('https://api-testnet.bybit.com',"apiKey", "secret");
```

### REST-queries (SPOT, DERIVATIVES packages)
```php
// Entrypoint:

BybitAPI::rest(
    string $endpointClassname,  // Имя класса эндпоинта, содержащий в себе все необходимые инструкции для обращений к апи
    [?IRequestInterface $options = null], // (обязательность смотри в описании интерфейсе обьекта параметров эндпоинта) Обьект с набором get или post параметров, которые запрашивает эндпоинт биржи
    [?int $outputMode = EnumOutputMode::DEFAULT_MODE] // Режим вывода результата запроса к апи биржи, по умолчанию, ответ от апи преобразуется в dto.
): IResponseInterface;


// REST example:

use Carpenstar\ByBitAPI\BybitAPI;
use Carpenstar\ByBitAPI\Spot\MarketData\BestBidAskPrice\BestBidAskPrice;
use Carpenstar\ByBitAPI\Spot\MarketData\BestBidAskPrice\Options\BestBidAskPriceOptions;
use Carpenstar\ByBitAPI\Spot\MarketData\BestBidAskPrice\Dto\BestBidAskPriceDto;

$bybit = new BybitAPI('https://api-testnet.bybit.com',"apiKey", "secret");

$options = (new BestBidAskPriceOptions())->setSymbol("BTCUSDT");

/** @var BestBidAskPriceDto $bestBidAskPrice */
$bestBidAskPrice = $bybit->rest(BestBidAskPrice::class, $options)->getBody()->fetch();

echo "Symbol: {$bestBidAskPrice->getSymbol()}" . PHP_EOL;
echo "Bid Price: {$bestBidAskPrice->getBidPrice()}" . PHP_EOL;
echo "Bid Qty: {$bestBidAskPrice->getBidQty()}" . PHP_EOL;
echo "Ask Price: {$bestBidAskPrice->getAskPrice()}" . PHP_EOL;
echo "Ask Qty: {$bestBidAskPrice->getAskQty()}" . PHP_EOL;
echo "Time: {$bestBidAskPrice->getTime()->format("Y-m-d H:i:s")}" . PHP_EOL;

/**
 * Result:
 *
 * Symbol: BTCUSDT
 * Bid Price: 27776
 * Bid Qty: 0.002492{}
 * Ask Price: 27776.01
 * Ask Qty: 0.004536
 * Time: 2023-05-09 18:03:57
 */
```

### Websockets connections
```php
// Entrypoint:

BybitAPI::websocket(
    string $webSocketChannelClassName,  // Имя класса базового канала, содержащий в себе все необходимые инструкции для соединения
    IWebSocketArgumentInterface $argument, // Обьект опций который необходим для настройки соединения
    IChannelHandlerInterface $channelHandler, // Пользовательский коллбэк сообщений пришедших от сервера.
    [int $mode = EnumOutputMode::MODE_ENTITY], // Тип сообщений передаваемых в коллбэк (dto или json)
    [int $wsClientTimeout = IWebSocketArgumentInterface::DEFAULT_SOCKET_CLIENT_TIMEOUT] // Таймаут сокет-клиента в милисекундах. По умолчанию: 1000
): void


// Websockets example:

use Carpenstar\ByBitAPI\BybitAPI;
use Carpenstar\ByBitAPI\WebSockets\Channels\Spot\PublicChannels\PublicTrade\KlineChannel;
use Carpenstar\ByBitAPI\WebSockets\Channels\Spot\PublicChannels\PublicTrade\Argument\KlineArgument;
use Carpenstar\ByBitAPI\Core\Enums\EnumIntervals;
use SomethingNameSpace\Directory\CustomChannelHandler;

$wsArgument = new KlineArgument(EnumIntervals::HOUR_1, "BTCUSDT");
$callbackHandler = new CustomChannelHandler();

$bybit = new BybitAPI("https://api-testnet.bybit.com", "apiKey", "secret");
$bybit->websocket(KlineChannel::class, $wsArgument, $callbackHandler);
```

## Доступные пакеты и эндпоинты:

* [SPOT](https://github.com/carpenstar/bybitapi-sdk-spot)
    * MARKET DATA
        * [Best Bid Ask Price](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---best-bid-ask-price)
        * [Instrument Info](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---instrument-info)
        * [Kline](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---kline)
        * [Last Traded Price](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---last-traded-price)
        * [Merged Order Book](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---merged-order-book)
        * [Public Trading Records](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---public-trading-records)
        * [Tickers](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---tickers)
        * [Order Book](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---order-book)
    * TRADE
        * [Place Order](https://github.com/carpenstar/bybitapi-sdk-spot#trade---place-order)
        * [Get Order](https://github.com/carpenstar/bybitapi-sdk-spot#trade---get-order)
        * [Cancel Order](https://github.com/carpenstar/bybitapi-sdk-spot#trade---cancel-order)


* [DERIVATIVES](https://github.com/carpenstar/bybitapi-sdk-derivatives)
    * MARKET DATA
        * [Funding Rate History](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---funding-rate-history)
        * [Index Price Kline](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---index-price-kline)
        * [Instrument Info](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---instrument-info)
        * [Kline](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---kline)
        * [Mark Price Kline](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---mark-price-kline)
        * [Open Interest](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---open-interest)
        * [Order Book](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---order-book)
        * [Public Trading History](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---public-trading-history)
        * [Risk Limit](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---risk-limit)
        * [Ticker Info](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---ticker-info)
    * CONTRACT
        * ACCOUNT
            * [Get Trading Fee Rate](https://github.com/carpenstar/bybitapi-sdk-derivatives#contract---account---get-trading-fee-rate)
            * [Wallet Balance](https://github.com/carpenstar/bybitapi-sdk-derivatives#contract---account---wallet-balance)
        * ORDER
            * [Place Order](https://github.com/carpenstar/bybitapi-sdk-derivatives#contract---account---order---place-order)
          

* [WEBSOCKETS](https://github.com/carpenstar/bybitapi-sdk-websockets)
    * SPOT
        * PUBLIC CHANNEL
            * [ORDER BOOK](https://github.com/carpenstar/bybitapi-sdk-websockets#public-channel---order-book)
            * [BOOKTICKER](https://github.com/carpenstar/bybitapi-sdk-websockets#public-channel---bookticker)
            * [TICKERS](https://github.com/carpenstar/bybitapi-sdk-websockets#public-channel---tickers)
            * [PUBLIC TRADE](https://github.com/carpenstar/bybitapi-sdk-websockets#public-channel---public-trade)
            * 
    * DERIVATIVES
        * PUBLIC CHANNEL
            * [ORDER BOOK](https://github.com/carpenstar/bybitapi-sdk-websockets#public-channel---order-book-1)
