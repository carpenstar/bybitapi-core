[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/badges/build.png?b=master)](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/carpenstar/bybitapi-sdk-core/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

# Bybit SDK

<p><b>NOTICE: This is an unofficial SDK, from an independent developer.</b></p>
<p>Any questions you are interested in regarding the settings, information about the bugs found, you can leave in Issues or by writing to mighty.vlad@gmail.com (ru, en)</p>
<p>And yes - the more stars, the more actively this project will develop :-)</p>

## Requirements

- PHP >= 7.4

## Installation

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

[Market Data - V5](https://github.com/carpenstar/bybitapi-sdk-v5-market) (<b>in developing...</b>)
```sh 
composer require carpenstar/bybitapi-sdk-v5-market
```

## API key generation

https://testnet.bybit.com/app/user/api-management - testnet  
https://www.bybit.com/app/user/api-management - production


## Application instance:

```php
use Carpenstar\ByBitAPI\BybitAPI;


$sdk = new BybitAPI();

// Setting the host for the next call to the exchange API
$sdk->setHost('https://api-testnet.bybit.com');

// Setting an API key that will be applied the next time you access the exchange API (optional, since the parameter is required when accessing private endpoints)
$sdk->setApiKey('apiKey'); 

// Setting a secret key that will be applied the next time you access the exchange API (optional, because the parameter is required when accessing private endpoints)
$sdk->setSecret('apiSecret');

// A wrapper function that allows you to set connection parameters with one call
$sdk->setCredentials('https://api-testnet.bybit.com', ['apiKey'], ['apiSecret']) 

// The function is used to access endpoints that do not require authorization (see endpoint description)
$sdk->publicEndpoint(<'Endpoint class name'>, <'DTO containing request parameters'>);

// The function is used to access endpoints that require authorization (see endpoint description)
$sdk->privateEndpoint(<'Endpoint class name'>, <'DTO containing request parameters'>)
```

## REST - queries

<p>All endpoints that can be called are divided into two types - public (not requiring authorization) and private (authorization is required for each request).</p>

```php
// The function is used to access endpoints that do not require authorization (see endpoint description)
$sdk->publicEndpoint(<'Endpoint class name'>, <'DTO containing request parameters'>);

// The function is used to access endpoints that require authorization (see endpoint description)
$sdk->privateEndpoint(<'Endpoint class name'>, <'DTO containing request parameters'>)
```

<p>Using the Derivatives/TickerInfo endpoint as an example, let’s make a request to the exchange API:</p>

```php

use Carpenstar\ByBitAPI\BybitAPI;
use Carpenstar\ByBitAPI\Derivatives\MarketData\TickerInfo\Request\TickerInfoRequest;
use Carpenstar\ByBitAPI\Derivatives\MarketData\TickerInfo\TickerInfo;

// Here we set only the host, because we don’t need authorization
$sdk = (new BybitAPI())->setCredentials('https://api-testnet.bybit.com');

// Prepare the endpoint for the request:
$endpoint = $sdk->publicEndpoint(TickerInfo::class, (new TickerInfoRequest())->setSymbol('BTCUSDT'));

// Start execution of the request:
$sdk->execute();
```

<p>
The execute() function always returns an object after completing a request, regardless of whether the request was successful
implementing the interface Carpenstar\ByBitAPI\Core\Interfaces\IResponseInterface
</p>

```php
namespace Carpenstar\ByBitAPI\Core\Interfaces;

use Carpenstar\ByBitAPI\Core\Objects\AbstractResponse;

interface IResponseInterface
{
 public function getReturnCode(): int; // Request completion code. If successful, it will always be 0
 public function getReturnMessage(): string; // Return message, usually 'OK'
 public function getExtendedInfo(): array; // Extended information
 public function getResult(): AbstractResponse; // Endpoint-specific DTO object containing the response from the exchange API
}
```

<p>To get the main body of the response, call the getResult function, which will return a DTO object containing information about the ticker.</p>
<p>In the case of the TickerInfo endpoint, this DTO will be an object that implements the Carpenstar\ByBitAPI\Derivatives\MarketData\TickerInfo\Interfaces\TickerInfoResponse interface</p>

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

<p>Next, calling the getTickerInfo() function will allow us to get an object with information about the ticker (the following DTO implements the ITickerInfoResponseItemInterface interface):</p>

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

<p>To summarize the above, as an example code this query might look like this:</p>

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

<p><b>An example of calls to the API, as well as a description of the available request/response objects can be found on the page of each endpoint</b></p>

## List of available REST endpoints

### ByBit API V3 - DERIVATIVES:

<table>
 <tr>
 <th colspan="5" style="text-align: left; font-weight: bold">MARKET DATA</th>
 </tr>
 <tr>
 <th style="text-align: center; font-weight: bold">Endpoint</th>
 <th style="text-align: center; font-weight: bold">Access type</th>
 <th style="text-align: center; font-weight: bold">View in directory</th>
 <th style="text-align: center; font-weight: bold">Official documentation</th>
 <th style="text-align: center; font-weight: bold">Language</th>
 </tr>
 <tr>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#market-data---funding-rate-history">Funding Rate History</a>
 </td>
 <td><b>publicEndpoint</b></td>
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/FundingRateHistory">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/funding-rate" target="_blank">go</a> </td>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/FundingRateHistory/README.md">EN</a>, <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/FundingRateHistory/README_ru.md">RU</a>
 </td>
 </tr>
 <tr>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#market-data---index-price-kline">Index Price Kline</a>
 </td>
 <td><b>publicEndpoint</b></td>
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/IndexPriceKline">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/index-kline" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/InstrumentInfo">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/instrument-info" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/Kline">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/kline" target="_blank">go</a></td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/MarkPriceKline">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/mark-kline" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/OpenInterest">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/open-interest" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/orderbook">go</a></td>
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/OrderBook" target="_blank" >go</a></td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/PublicTradingHistory">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/trade" target="_blank">go</a></td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/RiskLimit">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/risk-limit" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/MarketData/TickerInfo">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/public/ticker" target="_blank">go</a></td>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/TickerInfo/README.md">EN</a>,
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/TickerInfo/README_ru.md">RU</a>
 </td>
 </tr>
 <tr>
 <th colspan="5" style="text-align: left; font-weight: bold">CONTRACT - ACCOUNT</th>
 </tr>
<tr>
 <th style="text-align: center; font-weight: bold">Endpoint</th>
 <th style="text-align: center; font-weight: bold">Access type</th>
 <th style="text-align: center; font-weight: bold">View in directory</th>
 <th colspan="2" style="text-align: center; font-weight: bold">Official documentation</th>
 </tr>
 <tr>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---account---get-trading-fee-rate">Get Trading Fee Rate</a>
 </td>
 <td><b>privateEndpoint</b></td>
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Account/GetTradingFeeRate">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/fee-rate" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Account/WalletBalance">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/wallet" target="_blank">go</a></td>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/WalletBalance/README.md">EN</a>,
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/MarketData/WalletBalance/README_ru.md">RU</a>
 </td>
 </tr>
 <tr>
 <th colspan="5" style="text-align: left; font-weight: bold">CONTRACT - ORDER</th>
 </tr>
 <tr>
 <th style="text-align: center; font-weight: bold">Endpoint</th>
 <th style="text-align: center; font-weight: bold">Access type</th>
 <th style="text-align: center; font-weight: bold">View in directory</th>
 <th colspan="2" style="text-align: center; font-weight: bold">Official documentation</th>
 </tr>
 <tr>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---account---order---cancel-all-order">Cancel All Order</a >
 </td>
 <td><b>privateEndpoint</b></td>
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Order/CancelAllOrder">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/cancel-all" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Order/CancelOrder">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/cancel" target="_blank">go</a></td>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/CancelOrder/README.md">EN</a>,
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/CancelOrder/README_ru.md">RU</a>
 </td>
 </tr>
 <tr>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---account---order---get-open-orders">Get Open Orders</a >
 </td>
 <td><b>privateEndpoint</b></td>
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Order/GetOpenOrders">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/open-order" target="_blank">go</a> </td>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/GetOpenOrders/README.md">EN</a>,
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/GetOpenOrders/README_ru.md">RU</a>
 </td>
 </tr>
 <tr>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---account---order---get-order-list">Get Order List</a >
 </td>
 <td><b>privateEndpoint</b></td>
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Order/GetOrderList">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/order-list" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Order/PlaceOrder">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/place-order" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Order/ReplaceOrder">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/replace-order" target="_blank">go</a> </td>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/ReplaceOrder/README.md">EN</a>,
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/blob/master/src/Derivatives/Contract/ReplaceOrder/README_ru.md">RU</a>
 </td>
 </tr>
  <tr>
 <th colspan="5" style="text-align: left; font-weight: bold">CONTRACT - POSITION</th>
 </tr>
 <tr>
 <th style="text-align: center; font-weight: bold">Endpoint</th>
 <th style="text-align: center; font-weight: bold">Access type</th>
 <th style="text-align: center; font-weight: bold">View in directory</th>
 <th style="text-align: center; font-weight: bold">Official documentation</th>
 <th style="text-align: center; font-weight: bold">Language</th>
 </tr>
 <tr>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master#contract---position---get-closed-pnl">Get Closed PnL</a>
 </td>
 <td><b>privateEndpoint</b></td>
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/GetClosedPnL">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/closepnl" target="_blank">go</a></td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/GetExecutionList">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/execution-list" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/MyPosition">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/position-list" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SetAutoAddMargin">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/auto-margin" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SetLeverage">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/leverage" target="_blank">go</a></td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SetRiskLimit">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/set-risk-limit" target="_blank">go</a></td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SetTradingStop">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/trading-stop" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SwitchCrossIsolatedMargin">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/cross-isolated" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SwitchPositionMode">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/position-mode" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-derivatives/tree/master/src/Derivatives/Contract/Position/SwitchTpSlMode">go</a></td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/derivatives/contract/tpsl-mode" target="_blank">go</a> </td>
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
 <th style="text-align: center; font-weight: bold">Endpoint</th>
 <th style="text-align: center; font-weight: bold">Access type</th>
 <th style="text-align: center; font-weight: bold">View in directory</th>
 <th style="text-align: center; font-weight: bold">Official documentation</th>
 <th style="text-align: center; font-weight: bold">Language</th>
 </tr>
 <tr>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-spot#market-data---best-bid-ask-price">Best Bid Ask Price</a>
 </td>
 <td><b>publicEndpoint</b></td>
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/BestBidAskPrice">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/bid-ask" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/InstrumentInfo">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/instrument" target="_blank">go</a></td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/Kline">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/kline" target="_blank">go</a></td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/LastTradedPrice">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/last-price" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/MergedOrderBook">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/merge-depth" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/PublicTradingRecords">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/recent-trade" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/Tickers">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/tickers" target="_blank">go</a></td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/MarketData/OrderBook">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/public/depth" target="_blank">go</a></td>
 <td style="text-align: center">
 <a href="">EN</a>,
 <a href="">RU</a>
 </td>
 </tr>
  <tr>
 <th colspan="5" style="text-align: left; font-weight: bold">TRADE</th>
 </tr>
 <tr>
 <th style="text-align: center; font-weight: bold">Endpoint</th>
 <th style="text-align: center; font-weight: bold">Access type</th>
 <th style="text-align: center; font-weight: bold">View in directory</th>
 <th colspan="2" style="text-align: center; font-weight: bold">Official documentation</th>
 </tr>
 <tr>
 <td>
 <a href="https://github.com/carpenstar/bybitapi-sdk-spot#trade---place-order">Place Order</a>
 </td>
 <td><b>privateEndpoint</b></td>
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/PlaceOrder">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/place-order" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/GetOrder">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/get-order" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/CancelOrder">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/cancel" target="_blank">go</a></td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/BatchCancelOrder">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/batch-cancel" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/BatchCancelOrderById">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/cancel-by-id" target="_blank">go</a></td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/OpenOrders">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/open-order" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/OrderHistory">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/order-history" target="_blank">go</a> </td>
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
 <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Trade/TradeHistory">go</a> </td>
 <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/trade/my-trades" target="_blank">go</a> </td>
 <td style="text-align: center">
 <a href="">EN</a>,
 <a href="">RU</a>
 </td>
 </tr>

 <tr>
        <th colspan="5" style="text-align: left; font-weight: bold">LEVERAGE TOKEN</th>
    </tr>
    <tr>
        <th style="text-align: center; font-weight: bold">Endpoint</th>
        <th style="text-align: center; font-weight: bold">Access type</th>
        <th style="text-align: center; font-weight: bold">View in directory</th>
        <th colspan="2" style="text-align: center; font-weight: bold">Official documentation</th>
    </tr>
    <tr>
        <td><a href="">All Asset Info</a></td>
        <td><b>publicEndpoint</b></td>
        <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/LeverageToken/AllAssetInfo">go</a> </td>
        <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/etp/asset-info" target="_blank">go</a> </td>
        <td style="text-align: center">
            <a href="">EN</a>,
            <a href="">RU</a>
        </td>
    </tr>
    <tr>
        <td><a href="">Market Info</a></td>
        <td><b>publicEndpoint</b></td>
        <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/LeverageToken/MarketInfo">go</a> </td>
        <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/etp/market-info" target="_blank">go</a> </td>
        <td style="text-align: center">
            <a href="">EN</a>,
            <a href="">RU</a>
        </td>
    </tr>
    <tr>
        <td><a href="">Purchase</a></td>
        <td><b>publicEndpoint</b></td>
        <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/LeverageToken/Purchase">go</a> </td>
        <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/etp/purchase" target="_blank">go</a></td>
        <td style="text-align: center">
            <a href="">EN</a>,
            <a href="">RU</a>
        </td>
    </tr>
    <tr>
        <td><a href="">Purchase Redeem History</a></td>
        <td><b>publicEndpoint</b></td>
        <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/LeverageToken/PurchaseRedeemHistory">go</a> </td>
        <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/etp/order-history" target="_blank">go</a> </td>
        <td style="text-align: center">
            <a href="">EN</a>,
            <a href="">RU</a>
        </td>
    </tr>
    <tr>
        <td><a href="">Redeem</a></td>
        <td><b>publicEndpoint</b></td>
        <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/LeverageToken/Redeem">go</a> </td>
        <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/etp/redeem" target="_blank">go</a></td>
        <td style="text-align: center">
            <a href="">EN</a>,
            <a href="">RU</a>
        </td>
    </tr>
    <tr>
        <th colspan="5" style="text-align: left; font-weight: bold">ACCOUNT</th>
    </tr>
    <tr>
        <th style="text-align: center; font-weight: bold">Endpoint</th>
        <th style="text-align: center; font-weight: bold">Access type</th>
        <th style="text-align: center; font-weight: bold">View in directory</th>
        <th colspan="2" style="text-align: center; font-weight: bold">Official documentation</th>
    </tr>
    <tr>
        <td><a href="">Wallet Balance</a></td>
        <td><b>privateEndpoint</b></td>
        <td style="text-align: center"><a href="https://github.com/carpenstar/bybitapi-sdk-spot/tree/master/src/Spot/Account/WalletBalance">go</a> </td>
        <td style="text-align: center"><a href="https://bybit-exchange.github.io/docs/spot/wallet" target="_blank">go</a></td>
        <td style="text-align: center">
            <a href="">EN</a>,
            <a href="">RU</a>
        </td>
    </tr>
</table>



## WebSockets

<p>The carpenstar/bybitapi-sdk-websockets package is currently obsolete because it was not available before version 5 of the SDK kernel</p>
<p>You can use this package to receive data from public channels, however, in the very near future there will be
an update has been released that will not be backwards compatible with version 3.0.*</p>

[carpenstar/bybitapi-sdk-websockets](https://github.com/carpenstar/bybitapi-sdk-websockets) - go to package page