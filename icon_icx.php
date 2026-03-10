<?php
header('Content-Type: image/png');

$selectedToken = basename($_GET['token']);
$autoResolve = $_GET['autoResolve'];
$filename = 'icons/icon/' . $selectedToken . '.png';

if (file_exists($filename)) {
  $image = file_get_contents('icons/icon/' . $selectedToken . '.png');
} else {
  $image = file_get_contents('icons/icon/' . strtolower($selectedToken) . '.png');
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
