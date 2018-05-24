<?php
namespace wxpay;
include 'Base.php';
include 'Rsa.php';

class WxComPay extends Base
{
    private $params;
    const PAYURL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    const SEPAYURL = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo";
    const PKURL = "https://fraud.mch.weixin.qq.com/risk/getpublickey";
    const BANKPAY = "https://api.mch.weixin.qq.com/mmpaysptrans/pay_bank";
    public function getPuyKey(){
        $this->params = [
            'mch_id'    => self::MCHID,//商户ID
            'nonce_str' => md5(time()),
            'sign_type' => 'MD5'
        ];
         //将数据发送到接口地址
        return $this->send(self::PKURL);
    }
    public function comPay($data){
        //构建原始数据
        $this->params = [
            'mch_appid'         => self::APPID,//APPid,
            'mchid'             => self::MCHID,//商户号,
            'nonce_str'         => $data['nonceStr'], //随机字符串
            'partner_trade_no'  => $data['orderSn'], //商户订单号
            'openid'            => $data['openid'], //用户openid
            'check_name'        => 'NO_CHECK',//校验用户姓名选项 NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名
            //'re_user_name'    => '',//收款用户姓名  如果check_name设置为FORCE_CHECK，则必填用户真实姓名
            'amount'            => $data['amount'],//金额 单位分
            'desc'              => '收益提现',//付款描述
            'spbill_create_ip'  => $data['ip'],//调用接口机器的ip地址
        ];
        //将数据发送到接口地址
        return $this->send(self::PAYURL);
    }

    public function bankPay($arr){
        $rsa = new RSA(file_get_contents(EXTEND_PATH . 'wxpay/cert/newpubkey.pem'), '');
        $data = [
            'enc_bank_no'         => $rsa->public_encrypt($arr['enc_bank_no']),//收款方银行卡号RSA加密
            'enc_true_name'       => $rsa->public_encrypt($arr['enc_true_name']),//收款方姓名RSA加密
            'bank_code'           => $arr['bank_code'],//收款方开户行
            'amount'              => $arr['amount'],//付款金额
        ];

        $this->params = [
            'mch_id'              => self::MCHID,//商户号
            'partner_trade_no'    => $arr['orderSn'],//商户付款单号
            'nonce_str'           => $arr['nonceStr'], //随机串
            'enc_bank_no'         => $data['enc_bank_no'],//收款方银行卡号RSA加密
            'enc_true_name'       => $data['enc_true_name'],//收款方姓名RSA加密
            'bank_code'           => $data['bank_code'],//收款方开户行
            'amount'              => $data['amount'],//付款金额
        ];
         //将数据发送到接口地址
        return $this->send(self::BANKPAY);
    }


    public function searchPay($oid){
        $this->params = [
            'nonce_str'  => md5(time()),//随机串
            'partner_trade_no'  => $oid, //商户订单号
            'mch_id'  => self::MCHID,//商户号
            'appid'  => self::APPID //APPID
        ];
         //将数据发送到接口地址
        return $this->send(self::SEPAYURL);
    }
    public function sign(){
        return $this->setSign($this->params);
    }
    public function send($url){
        $res = $this->sign();
        $xml = $this->ArrToXml($res);
       $returnData = $this->postData($url, $xml);
       return $this->XmlToArr($returnData);
    }
}

