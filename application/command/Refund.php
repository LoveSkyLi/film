<?php
namespace app\command;

use app\api\model\FriendInfo;
use app\api\model\FriendOrder;
use app\api\model\RefundLog;
use library\Helper;
use library\WxTemplateMsg;
use think\console\Command;
use think\console\Input;
use think\console\Output;

use app\api\model\GroupList;
use app\api\model\PaymentConfig;
use app\api\model\OrderRefund;
use app\api\model\Order;
use think\Db;
use wxpay\Wxpay;

class Refund extends Command
{
	private $wxappid = '';
    private $mchId = '';
    private $mchKey = '';
	
    protected function configure()
    {
        $this->setName('refund')->setDescription('Activity end refund');
    }

    protected function execute(Input $input, Output $output)
    {
		//活动结束的退款
		$where = [
			'status' => 1,
            'order_status' => 1,
            'agreement' => 1,
			'agreement_time' =>['<', date("Y-m-d H:i:s",strtotime("-1 day"))],
		];
		$friendlist = \app\api\model\FriendInfo::where($where)->select();
		if (!$friendlist) {
            $output->writeln("没有满足条件的好友信息");
            exit;
        }
        foreach ($friendlist as $val) {
            $orderlist = FriendOrder::where([
                'friend_id' => $val['id'],
                'payment_status' => 1
            ])->select();

            foreach($orderlist as $value) {
                $orderSn = $value['order_sn'];
                $amount = $value['money_amount'];
                $uid = $value['uid'];
                $openid = $value['openid'];

                $nonceStr = Helper::createRandomStr(32);
                $params = [
                    'nonce_str' => $nonceStr,
                    'out_trade_no' => $orderSn,
                    'out_refund_no' => $orderSn,
                    'total_fee' => $amount,
                    'refund_fee' => $amount,
                ];
                $wxpay = new Wxpay();
                $resultArr = $wxpay->refund($params);
                if($resultArr['return_code'] == 'SUCCESS'){
                    Db::startTrans();
                    try{
                        RefundLog::create([
                            'uid' => $uid,
                            'order_sn' => $resultArr['out_trade_no'],
                            'refund_id' => $resultArr['refund_id'],
                            'refund_fee' => $resultArr['refund_fee'],
                            'refund_channel' => $resultArr['refund_channel'],
                            'status' => 1,
                        ]);

                        \app\api\model\FriendOrder::where([
                            'order_sn' => $resultArr['out_trade_no'],
                        ])->update([
                            'order_status' => 4,
                            'confirm_time' => date('Y-m-d H:i:s'),
                        ]);

                        FriendInfo::where([
                            'id' => $val['id']
                        ])->update(['order_status' => 4]);

                        // 提交事务
                        Db::commit();

                        $templateId = 'LGw2IbsaD81dbLisC3rZUi2cUHC-5DI7XgZIPvBYO_E';
                        $params = [
                            'touser' => $openid,
                            'template_id' => $templateId,
                            'page' => 'pages/show/index?id='.$value['friend_id'],
                            'form_id' => $value['prepay_id'],
                            'data' => [
                                'keyword1' => [
                                    'value' => '买入失败，点击进入小程序提取红包',
                                ],
                                'keyword2' => [
                                    'value' => '¥ '.$amount,
                                ],
                                'keyword3' => [
                                    'value' => $val['realname'],
                                ],
                                'keyword4' => [
                                    'value' => $val['user']['username'].'没有将你配对给'.$val['realname'],
                                ],
                            ]
                        ];
                        WxTemplateMsg::send($params);
                        $output->writeln("退款成功");
                    } catch (\Exception $e) {
                        // 回滚事务
                        Db::rollback();
                    }
                }else{
                    $output->writeln("退款失败");
                    exit;
                }
            }
        }

	}
	
	//证书验证
	private function curl_post_ssl($url, $vars, $appId, $second=30,$aHeader=array())
	{
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		//这里设置代理，如果有的话
		//curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
		//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		
		//以下两种方式需选择一种
		
		//第一种方法，cert 与 key 分别属于两个.pem文件
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLCERT,'/data/cert/'.$appId.'/apiclient_cert.pem');
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLKEY,'/data/cert/'.$appId.'/apiclient_key.pem');
		curl_setopt($ch,CURLOPT_CAINFO,'/data/cert/'.$appId.'/rootca.pem'); 
		
		//第二种方式，两个文件合成一个.pem文件
		//curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
	 
		if( count($aHeader) >= 1 ){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
		}
	 
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
		$data = curl_exec($ch);
		if($data){
			curl_close($ch);
			return $data;
		}else { 
			$error = curl_errno($ch);
			echo "call faild, errorCode:$error\n"; 
			curl_close($ch);
			return false;
		}
	}
	
	private function getPaymentConfig($appid)
    {
        $config = PaymentConfig::where([
            'appid' => $appid
        ])->find();
        if (!$config) {
            return ['error' => 1, 'msg' => '支付配置错误'];
        }
        if (!$config['wxappid'] || !$config['mch_id'] || !$config['mch_key']) {
            return ['error' => 1, 'msg' => '支付配置错误'];
        }
        $this->wxappid = $config['wxappid'];
        $this->mchId = $config['mch_id'];
        $this->mchKey = $config['mch_key'];
    }
	
	private function createRandomStr($length) {
        $str = array_merge(range(0,9), range('a','z'), range('A','Z'));
        shuffle($str);
        $str = implode('',array_slice($str,0,$length));
        return $str;
    }
	
	private function generateSign($arr)
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
	
	//数组转XML
	private function arrayToXml($arr) {
		$xml = "<xml>";
		foreach ($arr as $key => $val) {
			if (is_numeric($val)){
				$xml.="<".$key.">".$val."</".$key.">";
			}else{
				$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
			}
		}
		$xml .= "</xml>";
		return $xml;
	}

	//将XML转为array
	private function xmlToArray($xml) {
		//禁止引用外部xml实体
		libxml_disable_entity_loader(true);
		$values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $values;
	}
}