<?php

/*
 * Btcchina PHP sdk
 * @author Zhangwei(hemono@gmail.com)
 */
class Btcchina {
    private $accessKey = '';
    private $secretKey = '';
    private $API = 'https://api.btcchina.com/api_trade_v1.php';
    
    /**
     * 初始化API
     * 
     * @param type $accessKey 访问密匙 (Access Key)
     * @param type $secretKey 秘密密匙 (Secret Key)
     */
    function __construct($accessKey, $secretKey) {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }
    
    /**
     * 执行API调用
     * 
     * @param string $method 方法名
     * @param array $params  参数列表
     * @return array 返回数组
     */
    function _api($method, $params=array()){
        $mt = explode(' ', microtime());
        $ts = $mt[1] . substr($mt[0], 2, 6);

        $signature = array(
            'tonce' => $ts,
            'accesskey' => $this->accessKey,
            'requestmethod' => 'post',
            'id' => 1,
            'method' => $method
        );
        $queryString = http_build_query($signature) . '&params=' . implode(',', $params);

        $hash = hash_hmac('sha1', $queryString, $this->secretKey);
        $auth = base64_encode($this->accessKey.':'. $hash);

        $header = array(
            'Authorization: Basic ' . $auth,
            'Json-Rpc-Tonce: ' . $ts,
        );
        
        $postData = json_encode(array(
            'method' => $method,
            'params' => $params,
            'id' => 1,
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->API);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $res = curl_exec($ch);
        return json_decode($res, true);
    }
    
    /**
     * 下比特币买单
     * 
     * @param number $price 买 1 比特币所用人民币的价格，最多支持小数点后 5 位精度
     * @param number $amount 要买的比特币数量，最多支持小数点后 8 位精度
     * @return boolean	 如果下单成功，返回 true
     */
    function buyOrder($price, $amount) {
        return $this->_api(__FUNCTION__, func_get_args());
    }
    
    /**
     * 取消一个还未完全成交的挂单，其状态应该为“open”。
     * 
     * @param number $id 要取消的挂单的 ID
     * @return boolean	 如果取消挂单成功，返回 true
     */
    function cancelOrder($id) {
        return $this->_api(__FUNCTION__, func_get_args());
    }
    
    /**
     * 获取账户信息和余额。
     * 
     * @return object 包含如下对象：profile, balance, frozen
     */
    function getAccountInfo() {
        return $this->_api(__FUNCTION__, func_get_args());
    }
    
    /**
     * 获得用户全部充值记录
     * 
     * @param string $currency 目前仅支持“BTC”
     * @param boolean $pendingonly 默认为“true”。如果为“true”，仅返回尚未入账的比特币充值
     * @return object 对象：deposit
     */
    function getDeposits($currency, $pendingonly=true) {
        return $this->_api(__FUNCTION__, func_get_args());
    }


    /**
     * 获得完整的市场深度。返回全部尚未成交的买单和卖单。
     * 
     * @param number $limit 限制返回的买卖单数目。默认是买单卖单各10条。
     * @return object 对象：market_depth
     */
    function getMarketDepth ($limit=10) {
        return $this->getMarketDepth2($limit);
    }
    
    function getMarketDepth2 ($limit=10) {
        return $this->_api(__FUNCTION__, func_get_args());
    }
    
    /**
     * 获得挂单状态
     * 
     * @param number $id 挂单 ID
     * @return object	 返回对象：order
     */
    function getOrder($id) {
        return $this->_api(__FUNCTION__, func_get_args());
    }
    
    /**
     * 获得全部挂单的状态。
     * 
     * @param boolean $openonly 默认为“true”。如果为“true”，仅返回还未完全成交的挂单。
     * @return object[]	 对象数组：order
     */
    function getOrders($openonly=true) {
        return $this->_api(__FUNCTION__, func_get_args());
    }
    
    /**
     * 获取交易记录
     * 
     * @param string $type
     * @param integer $limit
     * @return	object[]  返回对象：transaction
     */
    function getTransactions($type, $limit=10) {
        return $this->_api(__FUNCTION__, func_get_args());
    }
    
    /**
     * 获取提现状态
     * 
     * @param number $id 提现 ID
     * @return object	 返回对象：withdrawal
     */
    function getWithdrawal($id) {
        return $this->_api(__FUNCTION__, func_get_args());
    }
    
    /**
     * 获取全部提现记录
     * 
     * @param string $currency 货币：目前仅支持“BTC”
     * @param boolean $pendingonly 默认为“true”。如果为“true”，仅返回尚未处理的提现记录
     */
    function getWithdrawals($currency, $pendingonly=true) {
        return $this->_api(__FUNCTION__, func_get_args());
    }
    
    /**
     * 发起比特币提现请求
     * 
     * @param string $currency 货币代码。可能值：BTC 或 CNY
     * @param number $amount 提现金额
     * @return integer	 返回提现 ID
     */
    function requestWithdrawal($currency, $amount) {
        return $this->_api(__FUNCTION__, func_get_args());
    }
    
    /**
     * 下比特币卖单
     * 
     * @param number $price 卖 1 比特币所用人民币的价格，最多支持小数点后 5 位精度
     * @param number $amount 要卖的比特币数量，最多支持小数点后 8 位精度
     * @return boolean	 如果下单成功，返回 true
     */
    function sellOrder($price, $amount) {
        return $this->_api(__FUNCTION__, func_get_args());
    }
}
