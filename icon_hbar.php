<?php
header('Content-Type: image/png');

$selectedToken = basename($_GET['token']);
$autoResolve = $_GET['autoResolve'];
$filename = 'icons/hbar/' . $selectedToken . '.png';

if (file_exists($filename)) {
  $image = file_get_contents('icons/hbar/' . $selectedToken . '.png');
} else {
  $image = file_get_contents('icons/hbar/' . strtolower($selectedToken) . '.png');

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