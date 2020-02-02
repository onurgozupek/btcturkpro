<?php
/**
 * BtcTurk | PRO API PHP wrapper class
 * @author Onur Gozupek <onur@gozupek.com>
 * @web OnurGozupek.com <https://onurgozupek.com>
 */
 
class BtcTurkPRO
{
    private $baseUrl;
    private $apiKey;
    private $apiSecret;
    public function __construct($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->baseUrl = 'https://api.btcturk.com/api/';
    }
    /**
     * Invoke API
     * @param string $method API method to call
     * @param array $params parameters
     * @param int $apiKey  use apikey (1) or not (0)
     * @param int $postMethod  get (0), post (1) or delete (3)
     * @return object
     */
    private function get_call($method, $params = array(), $apiKey = 0, $postMethod = 0)
    {
        $uri = $this->baseUrl.$method;
        if (!empty($params)) {
            if ($postMethod == 1) {
                $post_data = '{';
                foreach ($params as $key => $value)
                {
                    $post_data .= '"'.$key.'":';
                    if ($key == 'PairSymbol' || $key == 'newOrderClientId') {
                        $post_data .= '"'.$value.'"';
                    } else {
                        $post_data .= $value;
                    }
                    $post_data .= ', ';
                }
                $post_data = substr($post_data, 0, -2);
                $post_data .= '}';
            } else {
                $uri .= '?'.http_build_query($params);
            }
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, 'CURL_HTTP_VERSION_1_2');
            
        if ($postMethod == 1) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        } elseif ($postMethod == 3) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); 
        }
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        if ($apiKey == 1) {
            usleep(100000);
            $message = $this->apiKey.(time()*1000);
            $signatureBytes = hash_hmac('sha256', $message, base64_decode($this->apiSecret), true);
            $signature = base64_encode($signatureBytes);
            $nonce = time()*1000;
            $headers = array(
                'X-PCK: '.$this->apiKey,
                'X-Stamp: '.$nonce,
                'X-Signature: '.$signature,
                'Cache-Control: no-cache',
                'Content-Type: application/json',
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        $answer = json_decode($result);
        return $answer;
    }
    /**
     * Get the current tick values for a market.
     * @param string $symbol	literal for the market (ex: BTC_TRY)
     * @return array
     */
    public function getTicker($symbol='BTC_TRY')
    {
        $query = $this->get_call('v2/ticker');
        foreach ($query->data as $key => $value) {
            if ($value->pair == $symbol || $value->pairNormalized == $symbol) {
                return  $value;
                $pairValid = 1;
            }
        }
        $errorArray = array(
            'error'=>1,
            'pair'=> $symbol,
            'message'=>'Not enough volume',
        );
        
        if ($pairValid <> 1) { return json_decode(json_encode($errorArray), false); }
    }
    /**
     * Get the current ticks values for a market.
     * @return object
     */
    public function getAllTickers()
    {
        return $this->get_call('v2/ticker')->data;
    }
    /**
     * Get the orderbook for a given market
     * @param string $symbol literal for the market (ex: BTCTRY)
     * @return object
     */
    public function getOrderBook($symbol)
    {
        return $this->get_call('v2/orderbook', array('pairSymbol' => $symbol));
    }
    /**
     * Get the latest trades that have occured for a specific market
     * @param string $symbol literal for the market (ex: BTCTRY)
     * @param integer $count last trades count (default 50 max 1000)
     * @return object
     */
    public function getTrades($symbol,$count = 10)
    {
        return $this->get_call('v2/trades', array('pairSymbol' => $symbol, 'last' => $count));
    }
    /**
     * Displays historical daily pair data
     * @return object
     */
    public function getOhcl($symbol='BTC_TRY')
    {
        return $this->get_call('ohlcdata', array('pairSymbol' => $symbol));
    }
   
    /**
     * Get all orders that you currently have opened. A specific market can be requested
     * @param string $symbol literal for the market (ex: BTCTRY)
     * @return object
     */
    public function getOpenOrders($symbol)
    {
        $openOrders = $this->get_call('v1/openOrders', array('pairSymbol' => $symbol), 1, 0);

        if (count($openOrders->data->asks) > 0 || count($openOrders->data->bids) > 0) {
            return $openOrders->data;
        } else {
            return 'No open orders for '.$symbol;
        }

        //Usage: $request->getOpenOrders("BTCTRY");
    }
    /**
     * Retrieve the balance from your account for a specific currency
     * @param string $symbol literal for the currency (ex: BTC)
     * @return array
     */
    public function getBalances()
    {
        $query = $this->get_call('v1/users/balances', '', 1);
		    return  $query->data;
    }
    
    
	// API V1 Specific Functions
	
	
	/**
   * You can use this function to place order
   *
	 * quantity: "decimal", Mandatory for market or limit orders.
	 * price: "decimal", Price field will be ignored for market orders. Market orders get filled with different prices until your order is completely filled. There is a 5% limit on the difference between the first price and the last price. İ.e. you can't buy at a price more than 5% higher than the best sell at the time of order submission and you can't sell at a price less than 5% lower than the best buy at the time of order submission.
	 * stopPrice: "decimal", For stop orders
	 * newOrderClientId: "string", GUID if user did not set.
	 * orderMethod: "enum", "limit", "market" or "stoplimit"
	 * orderType: "enum", "buy", "sell"
	 * pairSymbol: "string", ex: "BTCTRY", "ETHTRY"
     * 
     * 
     * orderMethod: 1 market, 0 limit, 3 stoplimit
	 **/
	 
	  public function placeOrder($pairSymbol, $orderMethod, $orderType, $price = 0, $quantity = NULL, $stopPrice = 0)
    {   
        if ($orderMethod == 'market' || $orderMethod == 1) { $en_orderMethod = 1; }
        elseif ($orderMethod == 'stoplimit' || $orderMethod == 2) { $en_orderMethod = 2; }
        else { $en_orderMethod = 0; }
        
        if ($orderType == 'sell' || $orderType == 1) { $en_orderType = 1; }
        elseif ($orderType == 'buy' || $orderType == 0) { $en_orderType = 0; }

        return $this->get_call('v1/order', array('OrderMethod' => $en_orderMethod, 'OrderType' => $en_orderType, 'PairSymbol' => $pairSymbol, 'Quantity' => $quantity, 'Price' => $price, 'StopPrice' => $stopPrice, 'newOrderClientId' => 'OnurGozupekPHP'), 1, 1);
        //Usage: $request->placeOrder("XLMTRY","limit","sell","0.5853","20", "0");
    
    }
    
    /**
    * You can use this function to list all orders
    * 
    **/
    
    public function allOrders($pairSymbol, $orderid = NULL)
    {
        return $this->get_call('v1/allOrders',
            array('orderId' => $orderid,
            'pairSymbol' => $pairSymbol), 1, 0);
        //Usage: $request->allOrders("XLMTRY");
    }
    /**
    * You can use this function to list all open orders
    * 
    **/
    public function openOrders($pairSymbol)
    {
        return $this->get_call('v1/openOrders',
            array('pairSymbol' => $pairSymbol), 1, 0);
        //Usage: $request->openOrders("XLMTRY");
    }

    /**
     * Cancel a buy or sell order
     * @param integer $id id of sell or buy order
     * @return object
     */
    public function CancelOrder($id)
    {
        return $this->get_call('v1/order', array('id' => $id), 1, 3);
        //Usage: $request->CancelOrder(ORDERID);
    }

     /**
     * Retrieve your order history
     * @param string $type single parameter "buy" or array as object {"buy", "sell"}
     * @param string $symbol: single parameter for cryptocurrency or fiat "btc", "try" or array as object {"btc", "try", ...etc.}
     * @param string $startDate: Optional timestamp if null will return last 30 days
     * @param string $endDate: Optional timestamp if null will return last 30 days
     * @return object
     */
    public function UserTransactions($trntype = 'trade', $symbol = 'btc', $type = 'buy', $startDate = NULL, $endDate = NULL)
    {
        $params = array('type' => $type, 'symbol' => $symbol);
        if ($startDate) { $params['startDate'] = $startDate; }
        if ($endDate) { $params['endDate'] = $endDate; }
        return $this->get_call('v1/users/transactions/'.$trntype, $params, 1, 0);
    }
}
