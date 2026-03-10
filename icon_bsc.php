<?php
header('Content-Type: image/png');

$selectedToken = basename($_GET['token']);
$autoResolve = $_GET['autoResolve'];
$filename = 'icons/binanceSmartChain/' . $selectedToken . '.png';

if (file_exists($filename)) {
  $image = file_get_contents('icons/binanceSmartChain/' . $selectedToken . '.png');
} else {
  $image = file_get_contents('icons/binanceSmartChain/' . strtolower($selectedToken) . '.png');
  if ($image) {
  } else {
    $image = file_get_contents('https://github.com/trustwallet/assets/raw/master/blockchains/smartchain/assets/' . $selectedToken . '/logo.png');
    if ($image) {
    } else {
      $json_coingecko = file_get_contents('https://api.coingecko.com/api/v3/coins/binance-smart-chain/contract/' . $selectedToken);
      $coingeckoData = json_decode($json_coingecko);
      $coingeckoImageUrl = $coingeckoData->image->large ?? null;
      if ($coingeckoImageUrl) {
        $image = file_get_contents($coingeckoImageUrl);
      }
    }
    if ($image) {
      $file = 'icons/binanceSmartChain/' . $selectedToken . '.png';
      file_put_contents($file, $image, LOCK_EX);
      $file = 'icons/binanceSmartChain/' . strtolower($selectedToken) . '.png';
      file_put_contents($file, $image, LOCK_EX);
    } else {
      if ($autoResolve === 'false') {
        http_response_code(404);
        die();
      } else {
        $image = file_get_contents('icons/unknown.png');
      }
    }
  }
}

echo $image;

