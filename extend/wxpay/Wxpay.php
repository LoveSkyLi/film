<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 11:26
 */

namespace wxpay;

use Curl\Curl;
use library\Helper;

class Wxpay
{
    private $wxappid = 'wx589c6b80af1d478c';
    private $mchId = '1473466402';
    private $mchKey = 'luo3716251yangfan201702198319800';

    private function curlPost($url, $data, $options = [])
    {
        $curl  = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        if (isset($options['useCert']) && $options['useCert']) {
            $curl->setOpt(CURLOPT_SSLCERTTYPE,'PEM');
            $curl->setOpt(CURLOPT_SSLCERT,EXTEND_PATH . 'wxpay/cert/apiclient_cert.pem');
            $curl->setOpt(CURLOPT_SSLKEYTYPE,'PEM');
            $curl->setOpt(CURLOPT_SSLKEY,EXTEND_PATH . 'wxpay/cert/apiclient_key.pem');
            $curl->setOpt(CURLOPT_CAINFO,EXTEND_PATH . 'wxpay/cert/rootca.pem');
        }

        $curl->post($url, $data);
        return $curl->response;
    }

    /**
     * 获取RSA加密公钥
     * @return mixed
     */
    public function getPublicKey()
    {
        $url = 'https://fraud.mch.weixin.qq.com/risk/getpublickey';
        $params = [
            'mch_id' => $this->mchId,
            'nonce_str' => Helper::createRandomStr(32),
        ];
        $sign = $this->generateSign($params);
        $params['sign'] = $sign;
        $xmlParams = $this->arrayToXml($params);
        $options['useCert'] = true;
        $response = $this->curlPost($url, $xmlParams, $options);
        $result = $this->xmlToArray($response);
        return $result;
    }

    /**
     * 统一下单
     * @param $data
     * @return mixed
     */
    public function unifiedorder($data)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $params = [
            'appid' => $this->wxappid,
            'mch_id' => $this->mchId,
            'nonce_str' => $data['nonce_str'],
            'body' => $data['body'],
            'out_trade_no' => $data['out_trade_no'],
            'total_fee' => $data['total_fee'] * 100,
            'spbill_create_ip' => $data['spbill_create_ip'],
            'notify_url' => $data['notify_url'],
            'trade_type' => 'JSAPI',
            'openid' => $data['openid'],
        ];
        $sign = $this->generateSign($params);
        $params['sign'] = $sign;
        $xmlParams = arrayToXml($params);
        $options['useCert'] = false;
        $response = $this->curlPost($url, $xmlParams, $options);
        $result = xmlToArray($response);
        return $result;
    }

    /**
     * 退款
     * @param $data
     * @return mixed
     */
    public function refund($data)
    {
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $params = [
            'appid' => $this->wxappid,
            'mch_id' => $this->mchId,
            'nonce_str' => $data['nonce_str'],
            'out_trade_no' => $data['out_trade_no'],
            'out_refund_no' => $data['out_refund_no'],
            'total_fee' => $data['total_fee'] * 100,
            'refund_fee' => $data['refund_fee'] * 100,
            'op_user_id' => $this->mchId,
        ];
        $sign = $this->generateSign($params);
        $params['sign'] = $sign;
        $xmlParams = arrayToXml($params);
        $options['useCert'] = true;
        $response = $this->curlPost($url, $xmlParams, $options);
        $result = xmlToArray($response);
        return $result;
    }

    /**
     * 统一下单
     * @param $data
     * @return mixed
     */
    public function payBank($data)
    {
        $url = 'https://api.mch.weixin.qq.com/mmpaysptrans/pay_bank';
        $params = [
            'mch_id' => $this->mchId,
            'partner_trade_no' => $data['partner_trade_no'],
            'nonce_str' => $data['nonce_str'],
            'enc_bank_no' => $data['enc_bank_no'],
            'enc_true_name' => $data['enc_true_name'],
            'bank_code' => $data['bank_code'],
            'amount' => $data['amount'] * 100,
        ];
        $sign = $this->generateSign($params);
        $params['sign'] = $sign;
        $xmlParams = arrayToXml($params);
        $options['useCert'] = true;
        $response = $this->curlPost($url, $xmlParams, $options);
        $result = xmlToArray($response);
        return $result;
    }

    public function generateSign($arr)
    {
        $mchkey = $this->mchKey;
        ksort($arr);
        $str = '';
        foreach ($arr as $key => $val) {
            $str .= $key.'='.$val.'&';
        }
        $str = trim($str, '&');
        $signTemp = $str.'&key='.$mchkey;
        $sign = md5($signTemp);
        $sign = strtoupper($sign);
        return $sign;
    }
}