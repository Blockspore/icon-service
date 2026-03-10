# Icon service
Simple tool to get icons from blockchain tokens

### Supported chains
- Ethereum (ETH)
- Binance Smart Chain (BSC)
- Tron (TRC10 / TRC20)
- Ontology (ONT)
- Fantom (FTM)
- Polygon (MATIC)
- Avalanche (AVAX)
- Harmony (ONE)
- IoTeX (IOTX)
- Syscoin (SYS)
- ICON (ICX)
- Hedera (HBAR)
- BitTorrent Chain (BTTC)

### Usage

Ethereum:
```text
<img src="http://localhost/icon-service/icon_eth?token=0xc02aaa39b223fe8d0a0e5c4f27ead9083c756cc2">
```
Binance Smart Chain:
```text
<img src="http://localhost/icon-service/icon_bsc?token=0xbb4CdB9CBd36B01bD1cBaEBF2De08d9173bc095c">
```
Polygon:
```text
<img src="http://localhost/icon-service/icon_matic?token=0x7ceB23fD6bC0adD59E62ac25578270cFf1b9f619">
```
Avalanche:
```text
<img src="http://localhost/icon-service/icon_avax?token=0x49D5c2BdFfac6CE2BFdB6640F4F80f226bc10bAB">
```
Fantom:
```text
<img src="http://localhost/icon-service/icon_ftm?token=0x21be370D5312f44cB42ce377BC9b8a0cEF1A4C83">
```
Ontology:
```text
<img src="http://localhost/icon-service/icon_ont?token=17a58a4a65959c2f567e5063c560f9d09fb81284">
```
Tron TRC10:
```text
<img src="http://localhost/icon-service/icon_trc10?token=1000001">
```
Tron TRC20:
```text
<img src="http://localhost/icon-service/icon_trc20?token=TLvDJcvKJDi3QuHgFbJC6SeTj3UacmtQU3">
```
Harmony:
```text
<img src="http://localhost/icon-service/icon_one?token=0xcF664087a5bB0237a0BAd6742852ec6c8d69A27a">
```
IoTeX:
```text
<img src="http://localhost/icon-service/icon_iotx?token=io1hp6y4eqr90j7tmul4w2wa8pm7wx462hq0mg4tw">
```
Syscoin:
```text
<img src="http://localhost/icon-service/icon_sys?token=0x7731e5961C8659aE3e3e74fB0C60f8f28e36b944">
```
ICON:
```text
<img src="http://localhost/icon-service/icon_icx?token=cx88fd7df7ddff82f7cc735c871dc519838cb235bb">
```

### Optional parameters

| Parameter | Values | Description |
|---|---|---|
| `autoResolve` | `true` (default) / `false` | When `false`, returns HTTP 404 instead of the unknown placeholder if no icon is found |

### Icon sources (per chain)

| Chain | Sources tried in order |
|---|---|
| ETH | Local cache → TrustWallet → CoinGecko |
| BSC | Local cache → TrustWallet → CoinGecko |
| FTM | Local cache → TrustWallet → SpiritSwap (Layer3Org) |
| MATIC | Local cache → CoinGecko |
| AVAX | Local cache → ava-labs bridge tokens → TraderJoe |
| SYS | Local cache → PegaSys tokenlist → Tanenbaum tokenlist |
| ONE | Local cache → Mochiswap tokenlist |
| IOTX | Local cache → Mimo exchange |
| TRC20 | Local cache → Tronscan API |
| TRC10 | Local cache → Tronscan API |
| ONT | Local cache → Ontology Explorer (OEP4 / OEP5 / OEP8) |
| ICX / HBAR | Local cache only |

## Helpful links
- https://www.coingecko.com/en/api
- https://github.com/trustwallet/assets
- https://apilist.tronscan.org
- https://explorer.ont.io
