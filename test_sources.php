<?php
/**
 * Source Availability Report
 * Sends a HEAD request to each external dependency and reports its HTTP status.
 * Run this whenever you suspect an upstream source has gone offline.
 */

$sources = [
  'CoinGecko — ETH contract lookup'    => 'https://api.coingecko.com/api/v3/coins/ethereum/contract/0xc02aaa39b223fe8d0a0e5c4f27ead9083c756cc2',
  'CoinGecko — BSC contract lookup'    => 'https://api.coingecko.com/api/v3/coins/binance-smart-chain/contract/0xbb4CdB9CBd36B01bD1cBaEBF2De08d9173bc095c',
  'CoinGecko — Polygon contract lookup'=> 'https://api.coingecko.com/api/v3/coins/polygon-pos/contract/0x7ceB23fD6bC0adD59E62ac25578270cFf1b9f619',
  'CoinGecko — Avalanche contract lookup'=> 'https://api.coingecko.com/api/v3/coins/avalanche/contract/0x49D5c2BdFfac6CE2BFdB6640F4F80f226bc10bAB',
  'CoinGecko — Syscoin contract lookup'=> 'https://api.coingecko.com/api/v3/coins/syscoin/contract/0x7731e5961C8659aE3e3e74fB0C60f8f28e36b944',
  'TrustWallet — Ethereum'    => 'https://github.com/trustwallet/assets/raw/master/blockchains/ethereum/assets/0xc02aaa39b223fe8d0a0e5c4f27ead9083c756cc2/logo.png',
  'TrustWallet — BSC'         => 'https://github.com/trustwallet/assets/raw/master/blockchains/smartchain/assets/0xbb4CdB9CBd36B01bD1cBaEBF2De08d9173bc095c/logo.png',
  'TrustWallet — Fantom'      => 'https://github.com/trustwallet/assets/raw/master/blockchains/fantom/assets/0x21be370D5312f44cB42ce377BC9b8a0cEF1A4C83/logo.png',
  'Avalanche — ava-labs bridge tokens' => 'https://raw.githubusercontent.com/ava-labs/bridge-tokens/main/avalanche-tokens/',
  'Avalanche — TraderJoe tokenlists'   => 'https://raw.githubusercontent.com/traderjoe-xyz/joe-tokenlists/main/logos/',
  'Tronscan — TRC20'          => 'https://apilist.tronscan.org/api/token_trc20?contract=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t',
  'Tronscan — TRC10'          => 'https://apilist.tronscan.org/api/token?id=1002000&showAll=1',
  'Ontology Explorer — OEP4'  => 'https://explorer.ont.io/v2/tokens/oep4/00c59fcd27a562d6397883eab1f2fff56e58ef80',
  'Harmony — Mochiswap S3'    => 'https://s3-us-west-1.amazonaws.com/tokens.mochiswap.io/hashparty-default.tokenlist.json',
  'Syscoin — PegaSys tokenlist'     => 'https://raw.githubusercontent.com/Pollum-io/pegasys-tokenlists/master/pegasys.tokenlist.json',
  'Syscoin — Tanenbaum tokenlist'   => 'https://raw.githubusercontent.com/Pollum-io/pegasys-tokenlists/master/tanembaum.tokenlist.json',
  'IoTeX — Mimo exchange'     => 'https://api.mimo.exchange/api/token/image/io1hp6y4eqr90j7tmul4w2wa8pm7wx462hq0mg4tw',
  'SpiritSwap — Layer3Org icons'    => 'https://raw.githubusercontent.com/Layer3Org/spiritswap-tokens-list-icon/master/token-list/images/',
];

// Run all HEAD requests in parallel with curl_multi
$mh      = curl_multi_init();
$handles = [];

foreach ($sources as $name => $url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_NOBODY, true);         // HEAD only — no body
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 8);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
  curl_multi_add_handle($mh, $ch);
  $handles[$name] = $ch;
}

do {
  $status = curl_multi_exec($mh, $active);
  if ($active) {
    curl_multi_select($mh);
  }
} while ($active && $status === CURLM_OK);

$results = [];
foreach ($handles as $name => $ch) {
  $code          = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curlError     = curl_error($ch);
  $results[$name] = ['code' => $code, 'error' => $curlError];
  curl_multi_remove_handle($mh, $ch);
  curl_close($ch);
}
curl_multi_close($mh);

// Render report
$online  = array_filter($results, fn($r) => $r['code'] >= 200 && $r['code'] < 400);
$offline = array_filter($results, fn($r) => $r['code'] < 200 || $r['code'] >= 400);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Source Availability Report</title>
</head>
<body>
<div class="container py-4">
  <h1>Source Availability Report</h1>
  <p class="text-muted">Checked <?= count($results) ?> sources &mdash; <?= count($online) ?> online, <?= count($offline) ?> offline.</p>

  <?php if ($offline): ?>
  <h4 class="text-danger">&#10060; Offline / Unreachable</h4>
  <ul class="list-group mb-4">
    <?php foreach ($offline as $name => $r): ?>
    <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">
      <span><strong><?= htmlspecialchars($name) ?></strong><?= $r['error'] ? ' &mdash; ' . htmlspecialchars($r['error']) : '' ?></span>
      <span class="badge badge-light"><?= $r['code'] ?: 'no response' ?></span>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>

  <h4 class="text-success">&#10003; Online</h4>
  <ul class="list-group">
    <?php foreach ($online as $name => $r): ?>
    <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
      <strong><?= htmlspecialchars($name) ?></strong>
      <span class="badge badge-light"><?= $r['code'] ?></span>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
</body>
</html>

