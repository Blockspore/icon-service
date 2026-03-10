<?php


// https://raw.githubusercontent.com/Pollum-io/pegasys-tokenlists/master/pegasys.tokenlist.json
// https://raw.githubusercontent.com/Pollum-io/pegasys-tokenlists/master/tanembaum.tokenlist.json


header('Content-Type: image/png');

$selectedToken = basename($_GET['token']);
$autoResolve = $_GET['autoResolve'];
$filename = 'icons/syscoin/' . $selectedToken . '.png';


if (file_exists($filename)) {
  $image = file_get_contents('icons/syscoin/' . $selectedToken . '.png');
} else {
  $image = file_get_contents('icons/syscoin/' . strtolower($selectedToken) . '.png');
  if ($image) {
  } else {
    $json_pegasys = file_get_contents('https://raw.githubusercontent.com/Pollum-io/pegasys-tokenlists/master/pegasys.tokenlist.json');
    $infoPegasysData = json_decode($json_pegasys);
    foreach ($infoPegasysData->tokens as $infoPegasys) {
      if (strtolower($selectedToken) === strtolower($infoPegasys->address)) {
        $image = file_get_contents($infoPegasys->logoURI);
      }
    }
    if (!$image) {
      $json_tanembaum = file_get_contents('https://raw.githubusercontent.com/Pollum-io/pegasys-tokenlists/master/tanembaum.tokenlist.json');
      $infoTanembaumData = json_decode($json_tanembaum);
      foreach ($infoTanembaumData->tokens as $infoTanembaum) {
        if (strtolower($selectedToken) === strtolower($infoTanembaum->address)) {
          $image = file_get_contents($infoTanembaum->logoURI);
        }
      }
    }

    if ($image) {
      $file = 'icons/syscoin/' . $selectedToken . '.png';
      file_put_contents($file, $image, LOCK_EX);
      $file = 'icons/syscoin/' . strtolower($selectedToken) . '.png';
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