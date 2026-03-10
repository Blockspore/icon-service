<?php
header('Content-Type: image/png');

$selectedToken = basename($_GET['token']);
$autoResolve = $_GET['autoResolve'];


$filename = 'icons/ontology/' . $selectedToken . '.png';

if (file_exists($filename)) {
  $image = file_get_contents('icons/ontology/' . $selectedToken . '.png');
} else {
  $image = file_get_contents('icons/ontology/' . strtolower($selectedToken) . '.png');
  if ($image) {
  } else {
    $jsonExplorerIcons_oep4 = file_get_contents('https://explorer.ont.io/v2/tokens/oep4/' . $selectedToken);
    $ExplorerIcons_oep4 = json_decode($jsonExplorerIcons_oep4);

    if ($ExplorerIcons_oep4->msg == 'SUCCESS') {
      $image = file_get_contents($ExplorerIcons_oep4->result->logo);
    } else {
      $jsonExplorerIcons_oep5 = file_get_contents('https://explorer.ont.io/v2/tokens/oep5/' . $selectedToken);
      $ExplorerIcons_oep5 = json_decode($jsonExplorerIcons_oep5);

      if ($ExplorerIcons_oep5->msg == 'SUCCESS') {
        $image = file_get_contents($ExplorerIcons_oep5->result->logo);
      } else {
        $jsonExplorerIcons_oep8 = file_get_contents('https://explorer.ont.io/v2/tokens/oep8/' . $selectedToken);
        $ExplorerIcons_oep8 = json_decode($jsonExplorerIcons_oep8);

        if ($ExplorerIcons_oep8->msg == 'SUCCESS') {
          $image = file_get_contents($ExplorerIcons_oep8->result->logo);
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

    if ($image) {
      $file = 'icons/ontology/' . $selectedToken . '.png';
      file_put_contents($file, $image, LOCK_EX);
      $file = 'icons/ontology/' . strtolower($selectedToken) . '.png';
      file_put_contents($file, $image, LOCK_EX);
    }
  }
}

if ($image === false) {
  if ($autoResolve === 'false') {
    http_response_code(404);
    die();
  } else {
    $image = file_get_contents('icons/unknown.png');
  }
}

echo $image;

