<?php
header ('Content-Type: image/png');

$selectedToken = basename($_GET['token']);
$autoResolve = $_GET['autoResolve'];
$filename = 'icons/iotex/' . $selectedToken . '.png';

if (file_exists($filename)) {
  $image = file_get_contents('icons/iotex/' . $selectedToken . '.png');
} else {
  $image = file_get_contents('icons/iotex/' . strtolower($selectedToken) . '.png');
  if ($image) {
  } else {
    $image = file_get_contents('https://api.mimo.exchange/api/token/image/' . $selectedToken . '');

    if ($image) {
      $file = 'icons/iotex/' . $selectedToken . '.png';
      file_put_contents($file, $image, LOCK_EX);
      $file = 'icons/iotex/' . strtolower($selectedToken) . '.png';
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
