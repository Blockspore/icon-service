<?php
header('Content-Type: image/png');

$selectedToken = basename($_GET['token']);
$autoResolve = $_GET['autoResolve'];
$filename = 'icons/fantom/' . $selectedToken . '.png';

if (file_exists($filename)) {
  $image = file_get_contents('icons/fantom/' . $selectedToken . '.png');
} else {
  $image = file_get_contents('icons/fantom/' . strtolower($selectedToken) . '.png');
  if ($image) {
  } else {
    $image = file_get_contents('https://github.com/trustwallet/assets/raw/master/blockchains/fantom/assets/' . $selectedToken . '/logo.png');
    if ($image) {
    } else {
      // aad new token list with icons
      $image = file_get_contents('https://raw.githubusercontent.com/Layer3Org/spiritswap-tokens-list-icon/master/token-list/images/' . $selectedToken . '.png');
    }
    if ($image) {
      $file = 'icons/fantom/' . $selectedToken . '.png';
      file_put_contents($file, $image, LOCK_EX);
      $file = 'icons/fantom/' . strtolower($selectedToken) . '.png';
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
