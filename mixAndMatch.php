<?php
header ('Content-Type: image/png');

// Helper: fetch icon locally by re-using the existing icon_*.php files via output buffering
function fetchLocalIcon(string $chain, string $tokenAddress): string|false {
  $_GET['token']       = $tokenAddress;
  $_GET['autoResolve'] = 'false';
  ob_start();
  switch ($chain) {
    case 'TRX':  include __DIR__ . '/icon_trc20.php'; break;
    case 'ETH':  include __DIR__ . '/icon_eth.php';   break;
    case 'ICX':  include __DIR__ . '/icon_icx.php';   break;
    case 'BSC':  include __DIR__ . '/icon_bsc.php';   break;
    case 'ONE':  include __DIR__ . '/icon_one.php';   break;
    case 'ONT':  include __DIR__ . '/icon_ont.php';   break;
    case 'IOTX': include __DIR__ . '/icon_iotx.php';  break;
    case 'MATIC':include __DIR__ . '/icon_matic.php'; break;
    case 'AVAX': include __DIR__ . '/icon_avax.php';  break;
    case 'SYS':  include __DIR__ . '/icon_sys.php';   break;
    default: ob_end_clean(); return false;
  }
  $data = ob_get_clean();
  // A 404 response from the include means no icon found
  return (http_response_code() === 404 || empty($data)) ? false : $data;
}

// CoinGecko chain-id map for contract lookups by token name
$coingeckoChainMap = [
  'ethereum'        => 'ethereum',
  'binancesmartchain'=> 'binance-smart-chain',
  'iotex'           => 'iotex',
  'polygon'         => 'polygon-pos',
  'avalanche'       => 'avalanche',
  'syscoin'         => 'syscoin',
];

// Get unifi Tokens pairs
$json_unifiTokens = file_get_contents('data/tokens.json');
$unifiTokens = json_decode($json_unifiTokens);

// get smartContract from url
$smartContract = $_GET['smartContract'];
// Get image by data
$blockchain = $_GET['blockchain'];
$tokenName = strtoupper($_GET['tokenName']);

if ($smartContract):
  foreach ($unifiTokens as $unifiToken) {
    if ($smartContract == $unifiToken->smartContract) {
      $BlockchainShort = $unifiToken->BlockchainShort;
      $tokenAddress = $unifiToken->tokenAddress;
    }
  }

  if (!empty($BlockchainShort)) {
    $image = fetchLocalIcon($BlockchainShort, $tokenAddress);
  }
endif;

// Search by blockchain and token name
if ($blockchain):
  foreach ($unifiTokens as $unifiToken) {
    if ($blockchain == $unifiToken->Blockchain) {
      if ($tokenName == strtoupper($unifiToken->name)) {
        $BlockchainShort = $unifiToken->BlockchainShort;
        $tokenAddress = $unifiToken->tokenAddress;
      }
    }
  }

  if (!empty($BlockchainShort) && !empty($tokenAddress)) {
    $image = fetchLocalIcon($BlockchainShort, $tokenAddress);
  }

  // Search for correct image
  if (empty($image)) {
    $blockchainLower = strtolower($blockchain);

    if ($blockchainLower === 'icon') {
      $image = file_get_contents('icons/icon/icx.png');
      echo $image;
    } elseif ($blockchainLower === 'harmony') {
      $image = file_get_contents('icons/harmony/one.png');
      echo $image;
    } elseif ($blockchainLower === 'ontology') {
      $image = file_get_contents('icons/ontology/ONT_blue.png');
      echo $image;
    } elseif (isset($coingeckoChainMap[$blockchainLower]) && !empty($tokenAddress)) {
      $cgChain = $coingeckoChainMap[$blockchainLower];
      $json_coingecko = file_get_contents('https://api.coingecko.com/api/v3/coins/' . $cgChain . '/contract/' . $tokenAddress);
      $coingeckoData = json_decode($json_coingecko);
      $coingeckoImageUrl = $coingeckoData->image->large ?? null;
      if ($coingeckoImageUrl) {
        $image = file_get_contents($coingeckoImageUrl);
      }
    }
  }

endif;

if ($image) {
  echo $image;
} else {
  $blockchainLower = strtolower($blockchain);
  if ($blockchainLower === 'tron') {
    $image = file_get_contents('icons/tron/trc10/trx.png');
    echo $image;
  } elseif ($blockchainLower === 'ethereum') {
    $image = file_get_contents('icons/ethereum/eth.png');
    echo $image;
  } elseif ($blockchainLower === 'icon') {
    $image = file_get_contents('icons/icon/icx.png');
    echo $image;
  } elseif ($blockchainLower === 'binancesmartchain') {
    $image = file_get_contents('icons/binanceSmartChain/bnb.png');
    echo $image;
  } elseif ($blockchainLower === 'harmony') {
    $image = file_get_contents('icons/harmony/one.png');
    echo $image;
  } elseif ($blockchainLower === 'ontology') {
    $image = file_get_contents('icons/ontology/ONT_blue.png');
    echo $image;
  } elseif ($blockchainLower === 'iotex') {
    $image = file_get_contents('icons/iotex/iotex.png');
    echo $image;
  } elseif ($blockchainLower === 'polygon') {
    $image = file_get_contents('icons/polygon/matic.png');
    echo $image;
  } elseif ($blockchainLower === 'avalanche') {
    $image = file_get_contents('icons/avalanche/avalanche.png');
    echo $image;
  } elseif ($blockchainLower === 'syscoin') {
    $image = file_get_contents('icons/syscoin/SYS.png');
    echo $image;
  }
}

if ($image) {
} else {
  $image = file_get_contents('icons/unknown.png');

  echo $image;
}
