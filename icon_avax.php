<?php
header ('Content-Type: image/png');

$selectedToken = basename($_GET['token']);
$autoResolve = $_GET['autoResolve'];
$filename = 'icons/avalanche/' . $selectedToken . '.png';

if (file_exists($filename)) {
  $image = file_get_contents('icons/avalanche/' . $selectedToken . '.png');
} else {
  $image = file_get_contents('icons/avalanche/' . strtolower($selectedToken) . '.png');
  if ($image) {
  } else {
    $image = file_get_contents('https://raw.githubusercontent.com/ava-labs/bridge-tokens/main/avalanche-tokens/' . $selectedToken . '/logo.png');
    if ($image) {
    } else {
      $image = file_get_contents('https://raw.githubusercontent.com/traderjoe-xyz/joe-tokenlists/main/logos/' . $selectedToken . '/logo.png');
    }
    if ($image) {
      $file = 'icons/avalanche/' . $selectedToken . '.png';
      file_put_contents($file, $image, LOCK_EX);
      $file = 'icons/avalanche/' . strtolower($selectedToken) . '.png';
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