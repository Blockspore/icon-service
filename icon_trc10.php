<?php
header('Content-Type: image/png');

//ini_set('display_errors', '1');

$selectedToken = basename($_GET['token'] ?? '');
$autoResolve = $_GET['autoResolve'] ?? 'true';

if ($selectedToken === '_') {
    echo file_get_contents('icons/tron/trc10/trx.png');
    exit;
}

if (empty($selectedToken) || $selectedToken == '.' || $selectedToken == '..') {
    if ($autoResolve === 'false') {
        http_response_code(404);
        exit;
    }
    // Fallback?
}

$filename = 'icons/tron/trc10/' . $selectedToken . '.png';
$filenameLower = 'icons/tron/trc10/' . strtolower($selectedToken) . '.png';
$image = null;

if (file_exists($filename)) {
    $image = file_get_contents($filename);
} else {
    // Try lowercase local file
    if (file_exists($filenameLower)) {
        $image = file_get_contents($filenameLower);
    }

    // Try API if not found locally
    if (!$image) {
        $apiUrl = 'https://apilist.tronscan.org/api/token?id=' . $selectedToken . '&showAll=1';
        $apiData = @file_get_contents($apiUrl);
        if ($apiData) {
            $checkTronscan = json_decode($apiData);
            if ($checkTronscan && isset($checkTronscan->data) && is_array($checkTronscan->data) && isset($checkTronscan->data[0])) {
                $imgUrl = $checkTronscan->data[0]->imgUrl ?? null;
                if ($imgUrl) {
                    $image = @file_get_contents($imgUrl);
                }
            }
        }
    }

    // If found via lowercase or API, save to exact filename
    if ($image) {
        file_put_contents($filename, $image, LOCK_EX);
    } else {
        if ($autoResolve === 'false') {
            http_response_code(404);
            exit;
        } else {
            $image = @file_get_contents('icons/unknown.png');
        }
    }
}

if ($image) {
    echo $image;
}
