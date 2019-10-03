## BtcTurk | PRO API V1 & V2 PHP Client

# Genel Bilgiler
## Bu API ile neler yapılır?
-------------

    - [x] Tüm işlem çiftleri için anlık fiyat bilgisi alınabilir.
    - [x] Tüm işlem çiftleri için emir defterleri görüntülenebilir.
    - [x] Kullanıcı Alış/Satış işlem geçmişi görüntülenebilir.
    - [x] Açık Emirler görüntülenebilir.
    - [x] Tüm bakiyeler görüntülenebilir.
    - [x] Emir iptali yapılabilir.
    - [x] Alış ve satışta Market, Limit ve Stop Limit emir tipleri kullanılabilir.


# BtcTurk | PRO API PHP Class
-------------

Bu Class BtcTurk | PRO API v1 ve v2 için yapılmıştır (https://pro.btcturk.com/api).
Piyasa değerlerini kontrol etmek, bakiyeniz ile ticaret yapmak, kendi ticaret botunuzu yazmak vs. için kullanabilirsiniz.

Gereksinimler
-------------

* Bir BtcTurk Hesabı https://pro.btcturk.com
* Hesap > Api Erişimi kısmından bir Api hesabı oluşturmak

Kullanımı
-------------

	$key = 'API PUBLIC_KEY'; // API Erişimi sayfasında API anahtarı oluşturup Public Key ve API Secret'ı kullanın
	$secret = 'API SECRET';

	$request = new BtcTurkPRO($key, $secret);
	
	$list = $request->getBalances();


Örnekler
-----------
**Bakiyeleri listeleme**

> print_r( $request->getBalances() );


```json
Array
(
    [0] => stdClass Object
        (
            [asset] => TRY
            [assetname] => Türk Lirası
            [balance] => 2000000.0027651097639768
            [locked] => 69993.0206855312812313
            [free] => 130006.9820795784827455
            [precision] => 2
        )

    [1] => stdClass Object
        (
            [asset] => BTC
            [assetname] => Bitcoin
            [balance] => 5.0982810043145818
            [locked] => 4.0000000000000000
            [free] => 1.0982810043145818
            [precision] => 8
        )

    [2] => stdClass Object
        (
            [asset] => ETH
            [assetname] => Ethereum
            [balance] => 0
            [locked] => 0
            [free] => 0
            [precision] => 8
        )

    [3] => stdClass Object
        (
            [asset] => XRP
            [assetname] => Ripple
            [balance] => 0
            [locked] => 0
            [free] => 0
            [precision] => 4
        )

    [4] => stdClass Object
        (
            [asset] => LTC
            [assetname] => Litecoin
            [balance] => 0
            [locked] => 0
            [free] => 0
            [precision] => 8
        )

    [5] => stdClass Object
        (
            [asset] => USDT
            [assetname] => Tether
            [balance] => 0
            [locked] => 0
            [free] => 0
            [precision] => 2
        )

    [6] => stdClass Object
        (
            [asset] => XLM
            [assetname] => Stellar
            [balance] => 0
            [locked] => 0
            [free] => 0
            [precision] => 4
        )

    [7] => stdClass Object
        (
            [asset] => NEO
            [assetname] => Neo
            [balance] => 0
            [locked] => 0
            [free] => 0
            [precision] => 4
        )

)
```

**Gerçekleşen Emirleri listeleme**
> print_r( $request->getTrades("BTC_TRY", 50) );


```json
stdClass Object
(
    [data] => Array
        (
            [0] => stdClass Object
                (
                    [pair] => BTCTRY
                    [pairNormalized] => BTC_TRY
                    [numerator] => BTC
                    [denominator] => TRY
                    [date] => 1554894172934
                    [tid] => 012345678901234567
                    [price] => 27000.00
                    [amount] => 0.04197038
                    [side] => sell
                )

            [1] => stdClass Object
                (
                    [pair] => BTCTRY
                    [pairNormalized] => BTC_TRY
                    [numerator] => BTC
                    [denominator] => TRY
                    [date] => 1554721372421
                    [tid] => 012345678987654321
                    [price] => 24000.00
                    [amount] => 0.04197038
                    [side] => buy
                )
                ....
                ....
                ....
            [49] => stdClass Object
                (
                    [pair] => BTCTRY
                    [pairNormalized] => BTC_TRY
                    [numerator] => BTC
                    [denominator] => TRY
                    [date] => 1570129124303
                    [tid] => 637057259243043620
                    [price] => 46942.00
                    [amount] => 0.00424743
                    [side] => sell
                )
        )

    [success] => 1
    [message] => 
    [code] => 0
)
```

**Açık Emirleri Listeleme**
> print_r ( $request->getOpenorders("BTC_TRY") );

```json
stdClass Object
(
    [asks] => Array
        (
        )

    [bids] => Array
        (
            [0] => stdClass Object
                (
                    [id] => 01234567
                    [price] => 26250.00
                    [amount] => 0.15089865
                    [quantity] => 0.15089865
                    [stopPrice] => 0.00
                    [pairSymbol] => BTCTRY
                    [pairSymbolNormalized] => BTC_TRY
                    [type] => buy
                    [method] => limit
                    [orderClientId] => advanced
                    [time] => 0
                    [updateTime] => 1554991808470
                    [status] => Untouched
                    [leftAmount] => 0.15089865
                )

        )

)
```

**Emir ekleme (40.000TL fiyat ile 0.001 BTC'lik LIMIT Emir)**
> print_r ( $request->placeOrder("BTC_TRY", "limit", "buy", "40000", "0.001", "0") );

```json
stdClass Object
(
    [data] => stdClass Object
        (
            [id] => 12345678
            [quantity] => 0.01
            [price] => 40000.00
            [stopPrice] => 0
            [newOrderClientId] => OnurGozupekPHP
            [type] => buy
            [method] => limit
            [pairSymbol] => BTCTRY
            [pairSymbolNormalized] => BTC_TRY
            [datetime] => 1567538454301
        )

    [success] => 1
    [message] => OK
    [code] => 0
)
```

**Emir Ekleme (40.000TL fiyat, 39.800TL tetik fiyatı ile 0.001 BTC'lik STOP LIMIT Emir)**
> print_r ( $request->placeOrder("BTC_TRY", "limit", "buy", "40000", "0.001", "39800") );

```json
stdClass Object
(
    [data] => stdClass Object
        (
            [id] => 12345987
            [quantity] => 0.01
            [price] => 40000.00
            [stopPrice] => 39800.00
            [newOrderClientId] => OnurGozupekPHP
            [type] => buy
            [method] => limit
            [pairSymbol] => BTCTRY
            [pairSymbolNormalized] => BTC_TRY
            [datetime] => 1567539210301
        )

    [success] => 1
    [message] => OK
    [code] => 0
)
```

[Source](https://github.com/BTCTrader/broker-api-docs)

Donations/Support
-----

If you find this library to your liking and enjoy using it, please consider a donation to one of the following addresses:
* BTC: 18EzrJFZpTbBZ4aHe2w33rTDiQtWdHx3CM
* ETH: 0x40e9b726748DCC1a4D0f4cC7168b1215459a83Bd
