<?php
require_once("btcturkpro.class.php");

// API anahtarını https://pro.btcturk.com/hesap-ayarlari/api adresinden alabilirsiniz
$apiKey = "BTCTURKPRO_API_PUBLIC_KEY";
$apiSecret = "BTCTURKPRO_API_SECRET";

$request = new BtcTurkPRO ($apiKey, $apiSecret);

// Bakiyeleri listeleme
print_r( $request->getBalances() );

// Gerçekleşen Emirleri listeleme
print_r( $request->getTrades("BTC_TRY", 50) );

// Açık Emirleri Listeleme
print_r ( $request->getOpenorders("BTC_TRY") );

// Emir ekleme
// 40.000TL fiyat ile 0.001 BTC'lik LIMIT Emir
print_r ( $request->placeOrder("BTC_TRY", "limit", "buy", "40000", "0.001", "0") );

// 40.000TL fiyat, 39.800TL tetik fiyatı ile 0.001 BTC'lik LIMIT Emir
print_r ( $request->placeOrder("BTC_TRY", "limit", "buy", "40000", "0.001", "39800") );

?>
