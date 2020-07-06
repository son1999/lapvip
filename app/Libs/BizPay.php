<?php
namespace App\Libs;
/**
 * BizPay Lib
 * Version 1.0
 */
class BizPay
{
    static $_RSP_CODE_COMPLETED = 0;
    static $_RSP_CODE_NOT_COMPLETED = 99;
    static $_RSP_CODE_DUPLICATE_ORDER = 8;
    static $_RSP_CODE_NOT_EXISTED_ORDER = 7;
    static $_RSP_CODE_NULL_PAYGATE = 6;
    static $_RSP_CODE_INVALID_TOKEN = 4;
    static $_RSP_CODE_NULL_TOKEN = 9;
    static $_RSP_CODE_NULL_TRANSACTION_ID = 10;

    // dev
//    protected $_DOMAIN_URL = 'https://pay.todo.vn/';
//    protected $_API_URL = 'https://pay.todo.vn/payment/pay';
//    protected $_PROJECT_TOKEN = 'qD47KwCH38URuzzcw6bxOMBK5crG6vFk'; // token dev

    // live
    protected $_DOMAIN_URL = 'https://pay.bizfly.vn/';
    protected $_API_URL = 'https://pay.bizfly.vn/payment/pay'; // developing live mode
    protected $_PROJECT_TOKEN = 'tQOdkPtCN0fQKdhzqmi9ko214Cjh7JVh'; // developing live mode

    /**
     * Lấy ra danh sách campaign còn hiệu lực
     * @param $project_token (encrypt)
     * @return array
     */
    public function getCampaign()
    {
        $ch = curl_init($this->_DOMAIN_URL . 'api/campaign/list?project_token='.$this->encrypt_decrypt('encrypt', $this->_PROJECT_TOKEN));
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); // Time out 60s
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // connect time out 60s
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_error($ch)) {
            return false;
        }
        if ($status != 200) {
            curl_close($ch);
            return false;
        }
        // close curl
        curl_close($ch);
        return $result;
    }

    /**
     * Init order info present
     * @param $orderInfo
     * @return array
     */
    protected function _initOrder($orderInfo)
    {
        if (!isset($orderInfo['order_id']) || !isset($orderInfo['order_value']) || !isset($orderInfo['redirect_url']))
        {
            if (isset($orderInfo['redirect_url']))
            {
                return [
                    'success' => false,
                    'message' => 'Param invalid',
                    'data' => [
                        'redirect' => $orderInfo['redirect_url']
                    ]
                ];
            }
            else
            {
                return [
                    'success' => false,
                    'message' => 'Param invalid',
                    'data' => []
                ];
            }
        }

        // checking project token
        if (empty($this->_PROJECT_TOKEN))
        {
            return [
                'success' => false,
                'message' => 'Project token not found',
                'data' => []
            ];
        }

        $orderInfo['project_token'] = $this->_PROJECT_TOKEN;

        $orderInfo['secret_key'] = $this->_makeSecret($orderInfo);
        $orderInfo['redirect_url'] = urlencode($orderInfo['redirect_url']);

        return $orderInfo;
    }

    /**
     * make secret key function
     * @param $orderInfo array
     * @return string
     */
    private function _makeSecret($orderInfo)
    {
        return hash_hmac('SHA256', md5($orderInfo['order_id'] . $orderInfo['order_value'] . $orderInfo['project_token'] . (isset($orderInfo['recharge']) ? $orderInfo['recharge'] : 0)), md5($this->_PROJECT_TOKEN . '@vcpay'));
    }

    /**
     * go to url checkout without return to client if success
     * @param $orderInfo
     * @return string
     */
    public function buildUrlCheckout($orderInfo)
    {
        $orderInfo = $this->_initOrder($orderInfo);

        if (isset($orderInfo['success']) && !$orderInfo['success'])
        {
            return json_encode([
                'success' => false,
                'message' => 'Payment failed',
                'data' => [
                    'redirect' => $orderInfo['redirect_url']
                ]
            ]);
        }

        $queryString = http_build_query($orderInfo);
        if (!empty($queryString)){
            return json_encode([
                'success' => true,
                'message' => 'Get payment url success!',
                'data' => [
                    'redirect' => $this->_API_URL . "?" . $queryString
                ]
            ]);
        }else{
            return json_encode([
                'success' => false,
                'message' => 'Error building order.',
                'data' => [
                    'redirect' => $orderInfo['redirect_url']
                ]
            ]);
        }
    }

    public function createCurl($url, $params = []){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); // Time out 60s
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // connect time out 60s
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            'Accept: application/json',
//            'Content-Type: application/json'
//        ));

        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_error($ch)) {
            return false;
        }

        if ($status != 200) {
            curl_close($ch);
            return false;
        }
        // close curl
        curl_close($ch);
        return json_decode($result);
    }
    /**
     * verify url callback
     * @param $message
     * @return bool
     */
    public function verifyUrlCallback(&$message = '')
    {
        if (isset($_GET['error']) && isset($_GET['message']))
        {
            $message = $_GET['message'];
            return false;
        }

        if (!isset($_GET['order_id']) || !isset($_GET['created_order_date']) || !isset($_GET['secure_hash']))
        {
            $message = 'Thông tin đơn hàng trả về không hợp lệ';
            return false;
        }

        if ($this->_generalKeyOrder(
                $_GET['order_id'],
                $_GET['created_order_date'],
                $_GET['total_payment'],
                $_GET['order_id_client'],
                $_GET['vid'],
                $_GET['recharge']
            ) === $_GET['secure_hash'])
        {
            if (isset($_GET['recharge']) && $_GET['recharge'])
            {
                $message = 'Tài khoản VietID: '. $_GET['vid'] . ' đã được cộng tiền.';
            }
            else
            {
                $message = 'Thông tin thanh toán được chấp nhận.';
            }

            return true;
        }

        $message = 'Thông tin đơn hàng lỗi';
        return false;
    }

    /**
     * @param $orderId
     * @param $created_order_date
     * @param $totalPayment
     * @param $order_id_client
     * @param $vid
     * @param $total_payment_discount
     * @param $recharge
     * @return string
     */
    protected function _generalKeyOrder($orderId, $created_order_date, $totalPayment, $order_id_client, $vid, $recharge)
    {
        // new algorithm
        return hash_hmac('SHA256', md5(
            $orderId .
            $created_order_date .
            $totalPayment .
            $order_id_client .
            $vid .
            $recharge
        ), md5($totalPayment . '@paybizfly'));
    }

    /**
     * get order info
     * @param $order_client_id
     * @return object
     */
    public function getInfoOrder($order_client_id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_DOMAIN_URL.'api/order/info');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('order_client_id' => $order_client_id, 'project_token' => $this->_PROJECT_TOKEN)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_error($ch)) {
            return false;
        }
        if ($status != 200) {
            curl_close($ch);
            return false;
        }
        // close curl
        curl_close($ch);
        return json_decode($result);
    }

    /**
     * Tinh phi ship qua Giao hang tiet kiem
     * @param $shipFrom
     *  pick_address	no	String - Địa chỉ ngắn gọn để lấy nhận hàng hóa. Ví dụ: nhà số 5, tổ 3, ngách 11, ngõ 45
    pick_province	yes	String - Tên tỉnh/thành phố nơi lấy hàng hóa
    pick_district	yes	String - Tên quận/huyện nơi lấy hàng hóa
    pick_ward	no	String - Tên phường/xã nơi lấy hàng hóa
    pick_street	no	String - Tên đường/phố nơi lấy hàng hóa
     * @param $shipTo
     * address	no	String - Địa chỉ chi tiết của người nhận hàng, ví dụ: Chung cư CT1, ngõ 58, đường Trần Bình
    province	yes	String - Tên tỉnh/thành phố của người nhận hàng hóa
    district	yes	String - Tên quận/huyện của người nhận hàng hóa
    ward	no	String - Tên phường/xã của người nhận hàng hóa
    street	no	String - Tên đường/phố của người nhận hàng hóa
     * @param $weight
    weight	yes	Integer - Cân nặng của gói hàng, đơn vị sử dụng Gram
     * @param $value
    value	no	Integer - Giá trị thực của đơn hàng áp dụng để tính phí bảo hiểm, đơn vị sử dụng VNĐ
     * @param $transport
    transport	no	String - Phương thức vâng chuyển road ( bộ ) , fly (bay). Nếu phương thức vận chuyển không hợp lệ thì GHTK sẽ tự động nhảy về PTVC mặc định
     * @return mixed
     * fee.name	String - Tên gói cước được áp dụng, các giá trị có thể: area1, area2, area3
    fee.fee	Integer - Cước vận chuyển tính theo VNĐ
    fee.insurance_fee	Integer - Giá bảo hiểm tính theo VNĐ
    fee.delivery	Boolean - Hỗ trợ giao ở địa chỉ này chưa, nếu điểm giao đã được GHTK hỗ trợ giao trả về true, nếu GTHK chưa hỗ trợ giao đến khu vực này thì trả về false
     * Chi tiet tai: https://docs.giaohangtietkiem.vn/?php#t-nh-ph-v-n-chuy-n
     */
    public function getDeliveryCharges($shipFrom, $shipTo, $weight, $value, $transport)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_DOMAIN_URL.'api/order/delivery-charges');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
            'ship_from' => $shipFrom,
            'ship_to' => $shipTo,
            'weight' => $weight,
            'value' => $value,
            'transport' => $transport
        )));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_error($ch)) {
            return false;
        }
        if ($status != 200) {
            curl_close($ch);
            return false;
        }
        // close curl
        curl_close($ch);
        return $result;
    }

    /**
     * In phiếu giao đơn hàng
     * @param $orderPaymentId : String, mã đơn hàng thanh toán VD: GHTK-135896-1552878192

     * @return mixed

     * Chi tiet tai: https://docs.giaohangtietkiem.vn/?php#t-nh-ph-v-n-chuy-n
     */

    public function getLabel($orderPaymentId){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_DOMAIN_URL.'api/order/label');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
            'order_payment_id' => $orderPaymentId
        )));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json"
            )
        );
        $result = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_error($ch)) {
            return curl_error($ch);
        }
        if ($status != 200) {
            curl_close($ch);
            return false;
        }
        // close curl
        curl_close($ch);
        return $result;
    }

    /**
     * hủy đơn hàng
     * @param $orderPaymentId : String, mã đơn hàng thanh toán VD: GHTK-135896-1552878192

     * @return mixed

     * Chi tiet tai: https://docs.giaohangtietkiem.vn/?php#t-nh-ph-v-n-chuy-n
     */

    public function cancelOrder($orderPaymentId){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_DOMAIN_URL.'api/order/cancel-ghtk');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
            'order_payment_id' => $orderPaymentId
        )));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json"
            )
        );
        $result = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_error($ch)) {
            return curl_error($ch);
        }
        if ($status != 200) {
            curl_close($ch);
            return false;
        }
        // close curl
        curl_close($ch);
        return $result;
    }

    /**
     * @author phuong viet
     * encode and decode string project_token
     * @param $action
     * @param $string
     * @return string
     */
    protected function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'A8J2JFH3H5U53F';
        $secret_iv = 'F7ASF892HGD871';
        // hash
        $key = hash('ripemd160', $secret_key);
        $iv = substr(hash('ripemd160', $secret_iv), 0, 16);

        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    /**
     * verify url callback
     * @param $message
     * @return bool
     */
    public function verifyPostUrlCallback(&$message = '')
    {
        if (isset($_POST['error']) && isset($_POST['message']))
        {
            $message = $_POST['message'];
            return false;
        }

        if (!isset($_POST['order_id']) || !isset($_POST['created_order_date']) || !isset($_POST['secure_hash']))
        {
            $message = 'Thông tin đơn hàng trả về không hợp lệ';
            return false;
        }

        if ($this->_generalKeyOrder(
                $_POST['order_id'],
                $_POST['created_order_date'],
                $_POST['total_payment'],
                $_POST['order_id_client'],
                $_POST['vid'],
                $_POST['recharge']
            ) === $_POST['secure_hash'])
        {
            if (isset($_POST['recharge']) && $_POST['recharge'])
            {
                $message = 'Tài khoản VietID: '. $_POST['vid'] . ' đã được cộng tiền.';
            }
            else
            {
                $message = 'Thông tin thanh toán được chấp nhận.';
            }

            return true;
        }

        $message = 'Thông tin đơn hàng lỗi';
        return false;
    }

    /**
     * Recheck order
     * @param $orderInfo
     * @return array
     */
    public function checkOrder($order_client_id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_DOMAIN_URL.'api/order/check');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('order_id_client' => $order_client_id, 'project_token' => $this->_PROJECT_TOKEN)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_error($ch)) {
            return false;
        }
        if ($status != 200) {
            curl_close($ch);
            return false;
        }
        // close curl
        curl_close($ch);
        return $result;
    }
}
