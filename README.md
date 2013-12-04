btcchina-php-sdk
================

比特币中国API  
http://btcchina.org/api-trade-documentation-zh

创建API公钥私钥  
https://vip.btcchina.com/account/apikeys

代码示例
```
<?php
$accessKey = 'your access key'；
$secretKey = 'your secret key'；
$btc = new Btcchina($accessKey, $secretKey);
// 查看盘口
var_dump($btc->getMarketDepth());
// 购买1个比特币，价格¥100
var_dump($btc->buyOrder(100,1));
// 出售1个比特币，价格¥10000
var_dump($btc->sellOrder(10000,1));
```
