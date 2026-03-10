<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ---------------------------------------------------------------------------
// NOTE: All assets.unifiprotocol.com pool endpoints and data.unifi.report
// are offline. data/tokens.json is now maintained as a static file.
// This script only applies manual fixes on top of the existing static file.
// ---------------------------------------------------------------------------

// Load existing tokens so manual fixes can be appended / overwritten
$existingJson = file_get_contents('../data/tokens.json');
$allTokenes   = json_decode($existingJson, true) ?: [];

// ---------------------------------------------------------------------------
// Manual fixes
// ---------------------------------------------------------------------------
$allTokenes[] = ['name' => 'UP',     'tokenAddress' => 'TJ93jQZibdB3sriHYb5nNwjgkPPAcFR7ty',      'smartContract' => 'TUxqQp2qXUx7hT2F6Zx4hy85n8o9L9bzM9', 'Blockchain' => 'Tron',    'BlockchainShort' => 'TRX'];
$allTokenes[] = ['name' => 'BNB',    'tokenAddress' => 'BNB',                                       'smartContract' => 'BNB',                                   'Blockchain' => 'Binance', 'BlockchainShort' => 'BSC'];
$allTokenes[] = ['name' => 'WBNB',   'tokenAddress' => 'BNB',                                       'smartContract' => 'BNB',                                   'Blockchain' => 'Binance', 'BlockchainShort' => 'BSC'];
$allTokenes[] = ['name' => 'UP',     'tokenAddress' => '0xb4E8D978bFf48c2D8FA241C0F323F71C1457CA81', 'smartContract' => 'BNB',                                   'Blockchain' => 'Binance', 'BlockchainShort' => 'BSC'];
$allTokenes[] = ['name' => 'BURGER', 'tokenAddress' => 'BURGER',                                    'smartContract' => 'BNB',                                   'Blockchain' => 'Binance', 'BlockchainShort' => 'BSC'];
$allTokenes[] = ['name' => 'ETH',    'tokenAddress' => 'ETH',                                       'smartContract' => 'ETH',                                   'Blockchain' => 'Ethereum','BlockchainShort' => 'ETH'];
$allTokenes[] = ['name' => 'ONTd',   'tokenAddress' => 'ONT',                                       'smartContract' => 'ONT',                                   'Blockchain' => 'Ontology','BlockchainShort' => 'ONT'];
$allTokenes[] = ['name' => 'ong',    'tokenAddress' => 'ONT',                                       'smartContract' => 'ONT',                                   'Blockchain' => 'Ontology','BlockchainShort' => 'ONT'];

// ---------------------------------------------------------------------------
// Write output
// ---------------------------------------------------------------------------
$fp = fopen('../data/tokens.json', 'w');
fwrite($fp, json_encode($allTokenes, JSON_PRETTY_PRINT));
fclose($fp);
