[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/badges/build.png?b=master)](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

# Bybit SDK

***DISCLAIMER: Это неофициальный SDK, от независимого разработчика.   
Биржа ByBit не несет ответственность за работу этого кода, так же как и разработчик SDK не несет отвественность за работоспособность АПИ ByBit   
Любые интересующие вас вопросы относительно настройки, информацию о найденных багах или благодарности (слова ненависти :-)) вы можете в оставить в Issues или написав на почту mighty.vlad@gmail.com (ru, en)***

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

[Market Data - V5](https://github.com/carpenstar/bybitapi-sdk-v5-market) (<b>в разработке...</b>)
```sh 
composer require carpenstar/bybitapi-sdk-v5-market
```

## Генерация API-key

https://testnet.bybit.com/app/user/api-management - тестовая сеть (testnet)  
https://www.bybit.com/app/user/api-management - основная сеть (production)


## Экземпляр приложения:

```php
use Carpenstar\ByBitAPI\BybitAPI;


$sdk = new BybitAPI();

// Установка хоста для следующего обращения к апи биржи
$sdk->setHost('https://api-testnet.bybit.com');

// Установка ключа апи который будет применен при следующем обращении к апи биржи (опционально, т.к параметр необходим при обращении на приватные эндпоинтв)
$sdk->setApiKey('apiKey'); 

// Установка secret-ключа который будет применен при следующем обращении к апи биржи (опционально, т.r параметр необходим при обращении на приватные эндпоинтв)
$sdk->setSecret('apiSecret');

// функция "обертка", позволяющая установить параметры подключения одним вызовом
$sdk->setCredentials('https://api-testnet.bybit.com', ['apiKey'], ['apiSecret']) 

// Функция используется для обращений на эндпоинты к которым не требуется авторизация (см. описании эндпоинта)
$sdk->publicEndpoint(<Название класса Эндпоинта>, <DTO содержащее параметры запроса>);

// Функция используется для обращений на эндпоинты к которым требуется авторизация (см. описании эндпоинта)
$sdk->privateEndpoint(<Название класса Эндпоинта>, <DTO содержащее параметры запроса>)
```

## REST - запросы

Все эндпоинты которые могут быть вызваны, делятся на два типа - публичные (не требующие авторизации) и приватные (авторизация необходима при каждом запросе).

```php
// Функция используется для обращений на эндпоинты к которым не требуется авторизация (см. описании эндпоинта)
$sdk->publicEndpoint(<Название класса Эндпоинта>, <DTO содержащее параметры запроса>);

// Функция используется для обращений на эндпоинты к которым требуется авторизация (см. описании эндпоинта)
$sdk->privateEndpoint(<Название класса Эндпоинта>, <DTO содержащее параметры запроса>)
```

На примере эндпоинта Derivatives/TickerInfo сделаем запрос на биржевое апи:

```php

use Carpenstar\ByBitAPI\BybitAPI;
use Carpenstar\ByBitAPI\Derivatives\MarketData\TickerInfo\Request\TickerInfoRequest;
use Carpenstar\ByBitAPI\Derivatives\MarketData\TickerInfo\TickerInfo;

// Здесь устанавливаем только хост, т.к авторизация нам не нужна
$sdk = (new BybitAPI())->setCredentials('https://api-testnet.bybit.com');

// Подготовка эндпоинта к запросу:
$endpoint = $sdk->publicEndpoint(TickerInfo::class, (new TickerInfoRequest())->setSymbol('BTCUSDT'));

// Запуск исполнения запроса:
$sdk->execute();
```

Функция execute() после завершения запроса, вне зависимости от того был-ли запрос успешен, всегда возвращает объект 
реализующий интерфейс `Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface`

```php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

use Carpenstar\ByBitAPI\Core\Objects\AbstractResponse;

interface IResponseInterface
{
    public function getReturnCode(): int; // Код завершения запроса. В случае успешного завершения, всегда будет 0
    public function getReturnMessage(): string; // Возвращаемое сообщение, обычно 'OK'
    public function getExtendedInfo(): array; // Расширенная информация
    public function getResult(): AbstractResponse; // Специфичный для эндпоинта объект DTO содержащий в себе ответ от апи биржи
}
```

Для того чтобы получить основное тело ответа, вызовите функцию getResult, которая вернет объект DTO содержащий информацию о тикере.
В случае эндпоинта TickerInfo, этим DTO будет объект реализующий интерфейс `Carpenstar\ByBitAPI\Derivatives\MarketData\TickerInfo\Interfaces\TickerInfoResponse`

```php

namespace Carpenstar\ByBitAPI\Derivatives\MarketData\TickerInfo\Interfaces;


interface ITickerInfoResponseInterface
{
    /**
     * @return ITickerInfoResponseItemInterface
     */
    public function getTickerInfo(): ITickerInfoResponseItemInterface;
}
```

Далее, обратившись к функции getTickerInfo() позволит нам получить объект с информацией о тикере (следующее DTO реализует интерфейс ITickerInfoResponseItemInterface):

```php
namespace Carpenstar\ByBitAPI\Derivatives\MarketData\TickerInfo\Interfaces;

interface ITickerInfoResponseItemInterface
{
    /**
     * Symbol name
     * @return string
     */
    public function getSymbol(): string;

    /**
     * Best bid price
     * @return float
     */
    public function getBidPrice(): float;

    /**
     * Best ask price
     * @return float
     */
    public function getAskPrice(): float;

    /**
     * Last transaction price
     * @return float
     */
    public function getLastPrice(): float;

    /**
     * Direction of price change
     * @return string
     */
    public function getLastTickDirection(): string;

    /**
     * Price of 24 hours ago
     * @return float
     */
    public function getPrevPrice24h(): float;

    /**
     * Percentage change of market price relative to 24h
     * @return float
     */
    public function getPrice24hPcnt(): float;

    /**
     * The highest price in the last 24 hours
     * @return float
     */
    public function getHighPrice24h(): float;

    /**
     * Lowest price in the last 24 hours
     * @return float
     */
    public function getLowPrice24h(): float;

    /**
     * Hourly market price an hour ago
     * @return float
     */
    public function getPrevPrice1h(): float;

    /**
     * Mark price
     * @return float
     */
    public function getMarkPrice(): float;

    /**
     * Index price
     * @return float
     */
    public function getIndexPrice(): float;

    /**
     * Open interest
     * @return float
     */
    public function getOpenInterests(): float;

    /**
     * Turnover in the last 24 hours
     * @return float
     */
    public function getTurnover24h(): float;

    /**
     * Trading volume in the last 24 hours
     * @return float
     */
    public function getVolume24h(): float;

    /**
     * Funding rate
     * @return float
     */
    public function getFundingRate(): float;

    /**
     * Next timestamp for funding to settle
     * @return \DateTime
     */
    public function getNextFundingTime(): \DateTime;

    /**
     * Predicted delivery price. It has value when 30 min before delivery
     * @return float
     */
    public function getPredictedDeliveryPrice(): float;

    /**
     * Basis rate for futures
     * @return float
     */
    public function getBasisRate(): float;

    /**
     * Delivery fee rate
     * @return float
     */
    public function getDeliveryFeeRate(): float;

    /**
     * Delivery timestamp
     * @return \DateTime
     */
    public function getDeliveryTime(): \DateTime;

    /**
     * Open interest value
     * @return float
     */
    public function getOpenInterestValue(): float;
}
```

Подытоживая вышесказанное, в виде примера кода - этот запрос может выглядеть следующим образом:

```php
use Carpenstar\ByBitAPI\BybitAPI;
use Carpenstar\ByBitAPI\Derivatives\MarketData\TickerInfo\Interfaces\ITickerInfoResponseItemInterface;
use Carpenstar\ByBitAPI\Derivatives\MarketData\TickerInfo\Request\TickerInfoRequest;
use Carpenstar\ByBitAPI\Derivatives\MarketData\TickerInfo\TickerInfo;

$sdk = (new BybitAPI())->setCredentials('https://api-testnet.bybit.com');

$endpoint = $sdk->publicEndpoint(TickerInfo::class, (new TickerInfoRequest())->setSymbol('BTCUSDT'));

$endpointResponse = $sdk->execute();

echo "Return code: {$endpointResponse->getReturnCode()}\n";
echo "Return message: {$endpointResponse->getReturnMessage()}\n";

/** @var ITickerInfoResponseItemInterface $tickerInfo */
$tickerInfo = $endpointResponse->getResult()->getTickerInfo();

echo "Symbol: {$tickerInfo->getSymbol()}\n";
echo "Bid Price: {$tickerInfo->getBidPrice()}\n";
echo "Ask Price: {$tickerInfo->getAskPrice()}\n";
echo "Last Price: {$tickerInfo->getLastPrice()}\n";
echo "Last Tick Direction: {$tickerInfo->getLastTickDirection()}\n";
echo "Prev Price 24 hours: {$tickerInfo->getPrevPrice24h()}\n";
echo "Prev Price 24 hours(%): {$tickerInfo->getPrice24hPcnt()}\n";
echo "High Price 24 hours: {$tickerInfo->getHighPrice24h()}\n";
echo "Low Price 24 hours: {$tickerInfo->getLowPrice24h()}\n";
echo "Prev price 1 hour: {$tickerInfo->getPrevPrice1h()}\n";
echo "Mark Price: {$tickerInfo->getMarkPrice()}\n";
echo "Index Price: {$tickerInfo->getIndexPrice()}\n";
echo "Open Interest: {$tickerInfo->getOpenInterests()}\n";
echo "Open Interest Value: {$tickerInfo->getOpenInterestValue()}\n";
echo "Turnover 24 hours: {$tickerInfo->getTurnover24h()}\n";
echo "Volume 24 hours: {$tickerInfo->getVolume24h()}\n";
echo "Funding Rate: {$tickerInfo->getFundingRate()}\n";
echo "Next Funding Time: {$tickerInfo->getNextFundingTime()->format("Y-m-d H:i:s")}\n";
echo "Predicted Delivery Price: {$tickerInfo->getPredictedDeliveryPrice()}\n";
echo "Basis Rate: {$tickerInfo->getBasisRate()}\n";
echo "Delivery Fee Rate: {$tickerInfo->getDeliveryFeeRate()}\n";
echo "Open Interests Value: {$tickerInfo->getOpenInterestValue()}\n";
    
/** 
 * Return code: 0
 * Return message: OK
 * Symbol: BTCUSDT
 * Bid Price: 59933.6
 * Ask Price: 59935.7
 * Last Price: 59938
 * Last Tick Direction: ZeroMinusTick
 * Prev Price 24 hours: 58627.5
 * Prev Price 24 hours(%): 0.022352
 * High Price 24 hours: 63074.5
 * Low Price 24 hours: 58267.4
 * Prev price 1 hour: 59997
 * Mark Price: 59938
 * Index Price: 59957.26
 * Open Interest: 208384.158
 * Open Interest Value: 12490129662.2
 * Turnover 24 hours: 2907929540.5417
 * Volume 24 hours: 48504.964
 * Funding Rate: 8.407E-5
 * Next Funding Time: 2024-07-15 00:00:00
 * Predicted Delivery Price: 0
 * Basis Rate: 0
 * Delivery Fee Rate: 0
 * Open Interests Value: 12490129662.2 
 */
```  

<b>Пример обращений к апи, а так же описание доступных объектов запросов/ответов можно посмотреть на странице каждого эндпоинта</b>

## Список доступных REST-запросов

### ByBit API V3 - DERIVATIVES:

<table>
  <tr>
    <th colspan="5" style="text-align: left; font-weight: bold">MARKET DATA</th>
  </tr>
  <tr>
    <th style="text-align: center; font-weight: bold">Эндпоинт</th>
    <th style="text-align: center; font-weight: bold">Тип доступа</th>
    <th style="text-align: center; font-weight: bold">Смотреть в директории</th>
    <th style="text-align: center; font-weight: bold">Официальная документации</th>
    <th style="text-align: center; font-weight: bold">Язык</th>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#market-data---funding-rate-history">Funding Rate History</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/FundingRateHistory">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/funding-rate" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/FundingRateHistory/README.md">EN</a>, <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/FundingRateHistory/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#market-data---index-price-kline">Index Price Kline</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/IndexPriceKline">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/index-kline" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/IndexPriceKline/README.md">EN</a>, 
<a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/IndexPriceKline/README_ru.md">RU</a>
    </td>  
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#market-data---instrument-info">Instrument Info</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/InstrumentInfo">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/instrument-info" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/InstrumentInfo/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/InstrumentInfo/README_ru.md">RU</a>
    </td>    

</tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#market-data---kline">Kline</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/Kline">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/kline" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/Kline/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/Kline/README_ru.md">RU</a>
    </td>  
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#market-data---mark-price-kline">Mark Price Kline</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/MarkPriceKline">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/mark-kline" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/MarkPriceKline/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/MarkPriceKline/README_ru.md">RU</a>
    </td>  
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#market-data---open-interest">Open Interest</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/OpenInterest">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/open-interest" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/OpenInterest/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/OpenInterest/README_ru.md">RU</a>
    </td>  
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#market-data---order-book">Order Book</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/orderbook">перейти</a></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/OrderBook" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/OrderBook/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/OrderBook/README_ru.md">RU</a>
    </td>    
</tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#market-data---public-trading-history">Public Trading History</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/PublicTradingHistory">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/trade" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/PublicTradingHistory/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/PublicTradingHistory/README_ru.md">RU</a>
    </td>   
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#market-data---risk-limit">Risk Limit</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/RiskLimit">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/risk-limit" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/RiskLimit/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/RiskLimit/README_ru.md">RU</a>
    </td>   
</tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#market-data---ticker-info">Ticker Info</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/TickerInfo">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/ticker" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/TickerInfo/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/TickerInfo/README_ru.md">RU</a>
    </td>   
  </tr>
  <tr>
    <th colspan="5" style="text-align: left; font-weight: bold">CONTRACT - ACCOUNT</th>
  </tr>
  <tr>
    <th style="text-align: center; font-weight: bold">Эндпоинт</th>
    <th style="text-align: center; font-weight: bold">Тип доступа</th>
    <th style="text-align: center; font-weight: bold">Смотреть в директории</th>
    <th style="text-align: center; font-weight: bold">Официальная документации</th>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---account---get-trading-fee-rate">Get Trading Fee Rate</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Account/GetTradingFeeRate">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/fee-rate" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/GetTradingFeeRate/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/GetTradingFeeRate/README_ru.md">RU</a>
    </td> 
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---account---wallet-balance">Wallet Balance</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Account/WalletBalance">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/wallet" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/WalletBalance/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/WalletBalance/README_ru.md">RU</a>
    </td> 
  </tr>
  <tr>
    <th colspan="5" style="text-align: left; font-weight: bold">CONTRACT - ORDER</th>
  </tr>
  <tr>
    <th style="text-align: center; font-weight: bold">Эндпоинт</th>
    <th style="text-align: center; font-weight: bold">Тип доступа</th>
    <th style="text-align: center; font-weight: bold">Смотреть в директории</th>
    <th style="text-align: center; font-weight: bold">Официальная документации</th>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---account---order---cancel-all-order">Cancel All Order</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Order/CancelAllOrder">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/cancel-all" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/CancelAllOrder/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/CancelAllOrder/README_ru.md">RU</a>
    </td> 
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---account---order---cancel-order">Cancel Order</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Order/CancelOrder">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/cancel" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/CancelOrder/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/CancelOrder/README_ru.md">RU</a>
    </td> 
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---account---order---get-open-orders">Get Open Orders</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Order/GetOpenOrders">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/open-order" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/GetOpenOrders/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/GetOpenOrders/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---account---order---get-order-list">Get Order List</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Order/GetOrderList">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/order-list" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/GetOrderList/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/GetOrderList/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---account---order---place-order">Place Order</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Order/PlaceOrder">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/place-order" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/PlaceOrder/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/PlaceOrder/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---account---order---replace-order">Replace Order</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Order/ReplaceOrder">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/replace-order" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/ReplaceOrder/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/ReplaceOrder/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <th colspan="5" style="text-align: left; font-weight: bold">CONTRACT - POSITION</th>
  </tr>
  <tr>
    <th style="text-align: center; font-weight: bold">Эндпоинт</th>
    <th style="text-align: center; font-weight: bold">Тип доступа</th>
    <th style="text-align: center; font-weight: bold">Смотреть в директории</th>
    <th style="text-align: center; font-weight: bold">Официальная документации</th>
    <th style="text-align: center; font-weight: bold">Язык</th>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---position---get-closed-pnl">Get Closed PnL</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/GetClosedPnL">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/closepnl" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/GetClosedPnL/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/GetClosedPnL/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---position---get-execution-list">Get Execution List</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/GetExecutionList">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/execution-list" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/GetExecutionList/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/GetExecutionList/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---position---my-position">My Position</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/MyPosition">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/position-list" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/MyPosition/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/MyPosition/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---position---set-auto-add-margin">Set Auto Add Margin</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SetAutoAddMargin">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/auto-margin" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SetAutoAddMargin/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SetAutoAddMargin/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---position---set-leverage">Set Leverage</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SetLeverage">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/leverage" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SetLeverage/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SetLeverage/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---position---set-risk-limit">Set Risk Limit</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SetRiskLimit">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/set-risk-limit" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SetRiskLimit/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SetRiskLimit/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---position---set-trading-stop">Set Trading Stop</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SetTradingStop">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/trading-stop" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SetTradingStop/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SetTradingStop/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---position---switch-cross-isolated-margin">Switch Cross Isolated Margin</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SwitchCrossIsolatedMargin">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/cross-isolated" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SwitchCrossIsolatedMargin/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SwitchCrossIsolatedMargin/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---position---switch-position-mode">Switch Position Mode</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SwitchPositionMode">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/position-mode" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SwitchPositionMode/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SwitchPositionMode/README_ru.md">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---position---switch-tpsl-mode">Switch TpSl Mode</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SwitchTpSlMode">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/tpsl-mode" target="_blank">перейти</a></td>
    <td>
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SwitchTpSlMode/README.md">EN</a>, 
        <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Position/SwitchTpSlMode/README_ru.md">RU</a>
    </td>
  </tr>
</table>

### ByBit API V3 - SPOT:

<table>
  <tr>
    <th colspan="5" style="text-align: left; font-weight: bold">MARKET DATA</th>
  </tr>
  <tr>
    <th style="text-align: center; font-weight: bold">Эндпоинт</th>
    <th style="text-align: center; font-weight: bold">Тип доступа</th>
    <th style="text-align: center; font-weight: bold">Смотреть в директории</th>
    <th style="text-align: center; font-weight: bold">Официальная документации</th>
    <th style="text-align: center; font-weight: bold">Язык</th>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-spot#market-data---best-bid-ask-price">Best Bid Ask Price</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/BestBidAskPrice">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/bid-ask" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-spot#market-data---instrument-info">Instrument Info</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/InstrumentInfo">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/instrument" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-spot#market-data---kline">Kline</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/Kline">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/kline" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-spot#market-data---last-traded-price">Last Traded Price</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/LastTradedPrice">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/last-price" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-spot#market-data---merged-order-book">Merged Order Book</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/MergedOrderBook">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/merge-depth" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-spot#market-data---public-trading-records">Public Trading Records</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/PublicTradingRecords">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/recent-trade" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-spot#market-data---tickers">Tickers</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/Tickers">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/tickers" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-spot#market-data---order-book">Order Book</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/OrderBook">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/depth" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>

  <tr>
    <th colspan="4" style="text-align: left; font-weight: bold">TRADE</th>
  </tr>
  <tr>
    <th style="text-align: center; font-weight: bold">Эндпоинт</th>
    <th style="text-align: center; font-weight: bold">Тип доступа</th>
    <th style="text-align: center; font-weight: bold">Смотреть в директории</th>
    <th style="text-align: center; font-weight: bold">Официальная документации</th>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-spot#trade---place-order">Place Order</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/PlaceOrder">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/place-order" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-spot#trade---get-order">Get Order</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/GetOrder">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/get-order" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="https://github.com/carpenstar/bybitapi-sdk-spot#trade---cancel-order">Cancel Order</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/CancelOrder">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/cancel" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="">Batch Cancel Order</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/BatchCancelOrder">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/batch-cancel" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="">Batch Cancel Order By Id</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/BatchCancelOrderById">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/cancel-by-id" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="">Open Orders</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/OpenOrders">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/open-order" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="">Order History</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/OrderHistory">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/order-history" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="">Trade History</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/TradeHistory">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/my-trades" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>

  <tr>
    <th colspan="4" style="text-align: left; font-weight: bold">LEVERAGE TOKEN</th>
  </tr>
  <tr>
    <th style="text-align: center; font-weight: bold">Эндпоинт</th>
    <th style="text-align: center; font-weight: bold">Тип доступа</th>
    <th style="text-align: center; font-weight: bold">Смотреть в директории</th>
    <th style="text-align: center; font-weight: bold">Официальная документации</th>
  </tr>
  <tr>
    <td>
      <a href="">All Asset Info</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/LeverageToken/AllAssetInfo">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/etp/asset-info" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="">Market Info</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/LeverageToken/MarketInfo">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/etp/market-info" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="">Purchase</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/LeverageToken/Purchase">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/etp/purchase" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="">Purchase Redeem History</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/LeverageToken/PurchaseRedeemHistory">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/etp/order-history" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <td>
      <a href="">Redeem</a>
    </td>
    <td><b>publicEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/LeverageToken/Redeem">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/etp/redeem" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
  <tr>
    <th colspan="4" style="text-align: left; font-weight: bold">ACCOUNT</th>
  </tr>
  <tr>
    <th style="text-align: center; font-weight: bold">Эндпоинт</th>
    <th style="text-align: center; font-weight: bold">Тип доступа</th>
    <th style="text-align: center; font-weight: bold">Смотреть в директории</th>
    <th style="text-align: center; font-weight: bold">Официальная документации</th>
  </tr>
  <tr>
    <td>
      <a href="">Wallet Balance</a>
    </td>
    <td><b>privateEndpoint</b></td>
    <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Account/WalletBalance">перейти</a></td>
    <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/wallet" target="_blank">перейти</a></td>
    <td style="text-align: center">
        <a href="">EN</a>,
        <a href="">RU</a>
    </td>
  </tr>
</table>



## WebSockets

<p>Пакет carpenstar/bybitapi-sdk-websockets - в настоящий момент является устаревшим т.к он не был до 5 версии ядра SDK</p>
<p>Вы можете использовать этот пакет для получения данных с публичных каналов, однако, в самое ближайшее время будет 
выпущено обновление, которое не будет иметь обратную совместимость с текущей версией</p>

[carpenstar/bybitapi-sdk-websockets](https://github.com/carpenstar/bybitapi-sdk-websockets) - переход на страницу пакета