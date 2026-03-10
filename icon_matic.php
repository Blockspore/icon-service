<?php
header('Content-Type: image/png');

$selectedToken = $_GET['token'];
$autoResolve = $_GET['autoResolve'];
$filename = 'icons/polygon/' . $selectedToken . '.png';

if (file_exists($filename)) {
  $image = file_get_contents('icons/polygon/' . $selectedToken . '.png');
} else {
  $image = file_get_contents('icons/polygon/' . strtolower($selectedToken) . '.png');
  if (!$image) {
    $json_coingecko = file_get_contents('https://api.coingecko.com/api/v3/coins/polygon-pos/contract/' . $selectedToken);
    $coingeckoData = json_decode($json_coingecko);
    $coingeckoImageUrl = $coingeckoData->image->large ?? null;
    if ($coingeckoImageUrl) {
      $image = file_get_contents($coingeckoImageUrl);
      if ($image) {
        file_put_contents('icons/polygon/' . $selectedToken . '.png', $image, LOCK_EX);
        file_put_contents('icons/polygon/' . strtolower($selectedToken) . '.png', $image, LOCK_EX);
      }
    }
  }
  if (!$image) {
    if ($autoResolve === 'false') {
      http_response_code(404);
      die();
    } else {
      $image = file_get_contents('icons/unknown.png');
    }
  }
}
echo $image;
