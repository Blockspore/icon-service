<?php
header('Content-Type: image/png');

//ini_set('display_errors', '1');

$selectedToken = basename($_GET['token']);
$autoResolve = $_GET['autoResolve'];

$filename = 'icons/tron/trc10/' . $selectedToken . '.png';

//
if (file_exists($filename)) {
  $image = file_get_contents('icons/tron/trc10/' . $selectedToken . '.png');
} else {
  $image = file_get_contents('icons/tron/trc10/' . strtolower($selectedToken) . '.png');
  if ($image) {
  } else {
    $checkTronscan = json_decode(file_get_contents('https://apilist.tronscan.org/api/token?id=' . $selectedToken . '&showAll=1'));
    $image = file_get_contents($checkTronscan->data['0']->imgUrl);

  }
  if ($image) {
    $file = 'icons/tron/trc10/' . $selectedToken . '.png';
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
echo $image;

