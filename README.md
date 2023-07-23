[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/badges/build.png?b=master)](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

# Bybit SDK 

***DISCLAIMER: Это неофициальный SDK, от независимого разработчика.   
Биржа ByBit не несет ответственность за работу этого кода, так же как и разработчик SDK не несет отвественность за работоспособность АПИ ByBit
Любые интересующие вас вопросы относительно настройки, рассказать о найденных багах или поблагодарить (поругать) вы можете в Issue или написав на почту mighty.vlad@gmail.com (ru, en)***

## Cодержание:
* [Что это вообще такое?]()
* [Требования]()
* [Установка]()
* [Простые примеры использования]()
* [Как использовать]()
* [Список доступных эндпоинтов]()
* [Как переопределять компоненты?]()
* [Важные обьекты и словари ядра]()
* [Дорожная карта]()
* [Контрибьютинг]()

## Что это вообще такое?

...

## Требования

- PHP >= 7.4

## Установка

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

## Простые примеры использования:
* [Simple data farm](https://github.com/carpenstar/bybit-data-farm-example) (Websockets - Market data)
* [Simple widget with tickers](https://github.com/carpenstar/bybitapi-widget-example) (Websockets - Market data)

## Как использовать:
### Объект приложения:
В первую очередь нужно создать основной обьект приложения `Carpenstar\ByBitAPI\BybitAPI\BybitAPI()` содержащий 
в себе функционал способный потребоваться вам для работы с апи биржи.
Конструктор объекта принимает три параметра: хост (например, https://api-testnet.bybit.com), ключ апи и секрет которые вы можете сгенерировать в разделе   
https://www.bybit.com/app/user/api-management - mainnet   
https://testnet.bybit.com/app/user/api-management - testnet   
```php
use Carpenstar\ByBitAPI\BybitAPI;

$api = new BybitAPI($host, $apiKey, $secret);
```

### Виды обращений к апи:

АПИ биржи предусматривает два варианта обращений для получения данных, создания/отмены ордеров и т.д - это синхронные REST-запросы или подписка на каналы websockets.
В SDK реализовывается оба варианта взаимодействия.   
Ниже приведена схема вызова функции, с примером обращения.   
Полную информацию об эндпоинтах, параметрах и результатах запроса следует смотреть на странице подключаемого пакета. 

### REST-queries (пакеты: SPOT, DERIVATIVES)
```php
// Entrypoint:

BybitAPI::rest(
    string $endpointClassname,  // Имя класса эндпоинта, содержащий в себе все необходимые инструкции для обращений к апи
    [?IRequestInterface $options = null], // (обязательность смотри в описании интерфейсе обьекта параметров эндпоинта) Обьект с набором get или post параметров, которые запрашивает эндпоинт биржи
    [?int $outputMode = EnumOutputMode::DEFAULT_MODE] // Режим вывода результата запроса к апи биржи, по умолчанию, ответ от апи преобразуется в dto.
): IResponseInterface;


// REST example:

use Carpenstar\ByBitAPI\BybitAPI;
use Carpenstar\ByBitAPI\Spot\MarketData\OrderBook\OrderBook;
use Carpenstar\ByBitAPI\Spot\MarketData\OrderBook\Response\OrderBookResponse;
use Carpenstar\ByBitAPI\Spot\MarketData\OrderBook\Request\OrderBookRequest;
use Carpenstar\ByBitAPI\Spot\MarketData\OrderBook\Response\OrderBookPriceResponse;

$bybit = new BybitAPI('https://api-testnet.bybit.com',"apiKey", "secret");

$options = (new OrderBookRequest())
    ->setSymbol("ATOMUSDT")
    ->setLimit(5);

/** @var OrderBookResponse $orderBookData */
$orderBookData = $bybit->rest(OrderBook::class, $options)->getBody()->fetch();

echo "Time: {$orderBookData->getTime()->format('Y-m-d H:i:s')}" . PHP_EOL;
echo "Bids:" . PHP_EOL;
/** @var OrderBookPriceResponse $bid */
foreach ($orderBookData->getBids()->all() as $bid) {
    echo " - Bid Price: {$bid->getPrice()} Bid Quantity: {$bid->getQuantity()}" . PHP_EOL;
}
echo "Asks:" . PHP_EOL;
/** @var OrderBookPriceResponse $ask */
foreach ($orderBookData->getAsks()->all() as $ask) {
    echo " - Bid Price: {$ask->getPrice()} Bid Quantity: {$ask->getQuantity()}" . PHP_EOL;
}

/**
 * Result:
 * 
 * Time: 2023-05-12 10:15:41
 * Bids:
 * - Bid Price: 171.45 Bid Quantity: 19.29
 * - Bid Price: 104.15 Bid Quantity: 9.96
 * - Bid Price: 90.25 Bid Quantity: 99.72
 * - Bid Price: 81.05 Bid Quantity: 0.75
 * - Bid Price: 16.7 Bid Quantity: 5.98
 * Asks:
 * - Bid Price: 702.85 Bid Quantity: 1639.55
 * - Bid Price: 702.9 Bid Quantity: 0.01
 * - Bid Price: 703 Bid Quantity: 0.01
 * - Bid Price: 703.25 Bid Quantity: 0.01
 * - Bid Price: 704.8 Bid Quantity: 179.16
 */
```

### Websockets-подключения (пакет: WebSockets)
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

## Список доступных эндпоинтов:
   

* [SPOT](https://github.com/carpenstar/bybitapi-sdk-spot) 
  * MARKET DATA
    - [Best Bid Ask Price](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---best-bid-ask-price)
    - [Instrument Info](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---instrument-info)
    - [Kline](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---kline)
    - [Last Traded Price](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---last-traded-price)
    - [Merged Order Book](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---merged-order-book)
    - [Public Trading Records](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---public-trading-records)
    - [Tickers](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---tickers)
    - [Order Book](https://github.com/carpenstar/bybitapi-sdk-spot#market-data---order-book)
  * TRADE
    - [Place Order](https://github.com/carpenstar/bybitapi-sdk-spot#trade---place-order)
    - [Get Order](https://github.com/carpenstar/bybitapi-sdk-spot#trade---get-order)
    - [Cancel Order](https://github.com/carpenstar/bybitapi-sdk-spot#trade---cancel-order)   


* [DERIVATIVES](https://github.com/carpenstar/bybitapi-sdk-derivatives) 
  * MARKET DATA
    - [Funding Rate History](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---funding-rate-history)
    - [Index Price Kline](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---index-price-kline)
    - [Instrument Info](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---instrument-info)
    - [Kline](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---kline)
    - [Mark Price Kline](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---mark-price-kline)
    - [Open Interest](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---open-interest)
    - [Order Book](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---order-book)
    - [Public Trading History](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---public-trading-history)
    - [Risk Limit](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---risk-limit)
    - [Ticker Info](https://github.com/carpenstar/bybitapi-sdk-derivatives#market-data---ticker-info)
  * CONTRACT
    - ACCOUNT
      - [Get Trading Fee Rate](https://github.com/carpenstar/bybitapi-sdk-derivatives#contract---account---get-trading-fee-rate)
      - [Wallet Balance](https://github.com/carpenstar/bybitapi-sdk-derivatives#contract---account---wallet-balance)
    - ORDER
      - [Place Order](https://github.com/carpenstar/bybitapi-sdk-derivatives#contract---account---order---place-order)   
      

* [WEBSOCKETS](https://github.com/carpenstar/bybitapi-sdk-websockets) 
  * SPOT 
    - PUBLIC CHANNEL
      - [Order Book](https://github.com/carpenstar/bybitapi-sdk-websockets#public-channel---order-book)
      - [Bookticker](https://github.com/carpenstar/bybitapi-sdk-websockets#public-channel---bookticker)
      - [Tickers](https://github.com/carpenstar/bybitapi-sdk-websockets#public-channel---tickers)
      - [Public Trade](https://github.com/carpenstar/bybitapi-sdk-websockets#public-channel---public-trade)
  * DERIVATIVES
    - PUBLIC CHANNEL
      - [Order Book](https://github.com/carpenstar/bybitapi-sdk-websockets#public-channel---order-book-1)
 
## Как переопределять компоненты?

При необходимости, вы можете переопределить следующие компоненты sdk:
* REST:
- Обьект эндпоинта
- DTO параметров запроса
- DTO ответа от апи
* WebSockets:
- Обьект канала
- Обьект параметров подписки на канал
- Переопределить дефолтный коллбэк сообщения
  ...

## Важные обьекты и словари ядра:


### IResponseInterface

```php
interface IResponseInterface
{
    public function getReturnCode(): int;
    public function getReturnMessage(): string;
    public function getBody(): ICollectionInterface;
    public function getReturnExtendedInfo(): array;
    public function getTime(): \DateTime;

    public function bindEntity(string $className);
    public function handle(int $outputMode): IResponseInterface;
}
```

### Collections:

#### ArrayCollection ::ICollectionInterface

```php
class ArrayCollection
{
    public function push(?array $item = null): self // Добавление элемента в текущую коллекцию
    public function all(): array; // Извлечение всего содержимого коллекции
    public function fetch(); // Извлечение ТЕКУЩЕГО элемента коллекции и передвижение курсора на СЛЕДУЮЩИЙ элемент коллекции
    public function count(): int; // Количество элементов коллекции
}
```

#### EntityCollection ::ICollectionInterface

```php
class ArrayCollection
{
    public function push(?IResponseEntityInterface $item = null): self // Добавление элемента в текущую коллекцию
    public function all(): array; // Извлечение всего содержимого коллекции
    public function fetch(); // Извлечение ТЕКУЩЕГО элемента коллекции и передвижение курсора на СЛЕДУЮЩИЙ элемент коллекции
    public function count(): int; // Количество элементов коллекции
}
``` 

#### StringCollections ::ICollectionInterface 

```php
class ArrayCollection
{
    public function push(?string $item = null): self // Добавление элемента в текущую коллекцию
    public function all(): array; // Извлечение всего содержимого коллекции
    public function fetch(); // Извлечение ТЕКУЩЕГО элемента коллекции и передвижение курсора на СЛЕДУЮЩИЙ элемент коллекции
    public function count(): int; // Количество элементов коллекции
}
```

### Helpers:

#### \Carpenstar\ByBitAPI\Core\Helpers\DateTimeHelper

```php
class DateTimeHelper
{
    public static function makeFromTimestamp(int $timestamp): \DateTime // Преобразует timestamp ответа (uint64) в обьект DateTime
    public static function makeTimestampFromDateString(string $datetime): int // Преобразует строку даты/времени в таймштамп cо значением миллисекунд (unit64) 
}
```

### Dictionaries:

#### \Carpenstar\ByBitAPI\Core\Enums\EnumDerivativesCategory
```php
interface EnumDerivativesCategory
{
    const CATEGORY_PRODUCT_LINEAR = 'linear';
    const CATEGORY_PRODUCT_INVERSE = 'inverse';
    const CATEGORY_PRODUCT_OPTION = 'option';

}
```

#### \Carpenstar\ByBitAPI\Core\Enums\EnumHttpMethods
```php
interface EnumHttpMethods
{
    const GET = "GET";
    const POST = "POST";
}
```

#### \Carpenstar\ByBitAPI\Core\Enums\EnumIntervals
```php
interface EnumIntervals
{
    const MINUTE1 = '1m'; // 1 minute
    const MINUTE_3 = '3m'; // 3 minutes
    const MINUTE_5 = '5m'; // 5 minutes
    const MINUTE_15 = '15m'; // 15 minutes
    const MINUTE_30 = '30m'; // 30 minutes
    const HOUR_1 = '1h'; // 1 hour
    const HOUR_2 = '2h'; // 2 hours
    const HOUR_4 = '4h'; // 4 hours
    const HOUR_6 = '6h'; // 6 hours
    const HOUR_12 = '12h'; // 12 hours
    const DAY_1 = '1d'; // 1 day
    const WEEK_1 = '1w'; // 1 week
    const MONTH_1 = '1m'; // 1 month
}
```

#### \Carpenstar\ByBitAPI\Core\Enums\EnumOrderCategory
```php
interface EnumOrderCategory
{
    const NORMAL_ORDER = 0; // Default mode
    const TPSL_ORDER = 1; // TakeProfit/StopLoss mode
}
```

#### \Carpenstar\ByBitAPI\Core\Enums\EnumOrderType
```php
interface EnumOrderType
{
    const LIMIT = "Limit";
    const MARKET = "Market";
    const LIMIT_MAKER = "Limit_maker";
}
```

#### \Carpenstar\ByBitAPI\Core\Enums\EnumOutputMode
```php
interface EnumOutputMode
{
    const MODE_ENTITY = 0;
    const MODE_ARRAY = 1;
    const MODE_JSON = 2;
}
```

#### \Carpenstar\ByBitAPI\Core\Enums\EnumSide
```php
interface EnumSide
{
    const BUY = "Buy";
    const SELL = "Sell";
}
```

#### \Carpenstar\ByBitAPI\Core\Enums\EnumTimeInForce
```php
interface EnumTimeInForce 
{
    const GOOD_TILL_CANCELED = "GTC";
    const FILL_OR_KILL = "FOK";
    const IMMEDIATE_OR_CANCEL = "IOC";
}
```

## Дорожная карта


...

## Контрибьютинг


...