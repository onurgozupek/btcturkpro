<?php
require_once("btcturkpro.class.php");

// API anahtarını https://pro.btcturk.com/hesap-ayarlari/api adresinden alabilirsiniz
// You can get your api key at https://pro.btcturk.com/hesap-ayarlari/api
$apiKey = "BTCTURKPRO_API_PUBLIC_KEY";
$apiSecret = "BTCTURKPRO_API_SECRET";

$request = new BtcTurkPRO ($apiKey, $apiSecret);

// Bakiyeleri listeleme
// get balances
print_r( $request->getBalances() );

// Gerçekleşen Emirleri listeleme
// list successful trades
print_r( $request->getTrades("BTC_TRY", 50) );

// Açık Emirleri Listeleme
// List open orders
print_r ( $request->getOpenorders("BTC_TRY") );

// 40.000TL fiyat ile 0.001 BTC'lik LIMIT Emir
// Limit order
print_r ( $request->placeOrder("BTC_TRY", "limit", "buy", "40000", "0.001", "0") );

// 40.000TL fiyat, 39.800TL tetik fiyatı ile 0.001 BTC'lik LIMIT Emir
// Limir order with stop price
print_r ( $request->placeOrder("BTC_TRY", "limit", "buy", "40000", "0.001", "39800") );

// Emir iptali
// Cancel order
print_r ( $request->CancelOrder(1234567890) );

?>
