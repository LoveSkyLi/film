<?php
namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

use app\api\model\GroupList;
use app\api\model\PaymentConfig;
use app\api\model\OrderRefund;
use app\api\model\Order;
use think\Db;
use think\Request;

class Withdraw extends Command
{
	private $wxappid = '';
    private $mchId = '';
    private $mchKey = '';
	
    protected function configure()
    {
        $this->setName('withdraw')->setDescription('User account withdraw');
    }

    protected function execute(Input $input, Output $output)
    {
		//获取没成团的团信息
		$where = [
			'status' => 1,
			'create_time' =>['<',date("Y-m-d H:i:s",strtotime("-1 day"))],
		];
		$grouplist = \app\api\model\GroupList::where($where)->update([
			'status' => 3,
		]);
		$grouplist = \app\api\model\GroupList::where(['status' => 3])->field(['id'])->select();
		foreach($grouplist as $val){
			$status = 0;
			$orderlist = \app\api\model\Order::where(['grouplist_id' => $val['id'],'order_status' => 5])->field(['appid,order_sn,money_amount,uid'])->select();
			if(!empty($orderlist)){
				foreach($orderlist as $value){
					$appId = $value['appid'];
					$orderSn = $value['order_sn'];
					$amount = $value['money_amount'];
					$uid = $value['uid'];

					$url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
					//获取支付配置
					$this->getPaymentConfig($appId);
					$nonceStr = $this->createRandomStr(32);
                    $ip = Request::instance()->ip();
					$params = [
						'appid' => $this->wxappid,
						'mch_id' => $this->mchId,
						'nonce_str' => $nonceStr,
						'out_trade_no' => $orderSn,
						'out_refund_no' => $orderSn,
						'total_fee' => $amount*100,
						'refund_fee' => $amount*100,
						'op_user_id' => $this->mchId,
					];


                    $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
                    $params = [
                        'mch_appid' => $this->wxappid,
                        'mchid' => $this->mchId,
                        'nonce_str' => $nonceStr,
                        'partner_trade_no' => $orderSn,
                        'check_name' => 'NO_CHECK',
                        'amount' => $money * 100,
                        'desc' => '余额提现',
                        'spbill_create_ip' => $ip,
                        'openid' => $openId,
                    ];



					$sign = $this->generateSign($params);
					$params['sign'] = $sign;
					$xmlParams = $this->arrayToXml($params);
					if (!file_exists('/data/cert/'.$appId)) {
					    $status = 1;
                        $output->writeln("App".$appId."-证书不存在");
                        continue;
                    }
					$data = $this->curl_post_ssl($url, $xmlParams,$appId);
					$resultArr = $this->xmlToArray($data);
				
					if($resultArr['return_code'] == 'SUCCESS'){
						Db::startTrans();
						try{
							$orderrefund = OrderRefund::create([
								'appid' => $appId,
								'uid' => $uid,
								'order_sn' => $resultArr['out_trade_no'],
								'refund_id' => $resultArr['refund_id'],
								'refund_fee' => $resultArr['refund_fee'],
								'refund_channel' => $resultArr['refund_channel'],
								'status' => 1,
							]);
							$list = \app\api\model\Order::where([
								'order_status' => 5,
								'order_sn' => $resultArr['out_trade_no'],
							])->update([
								'order_status' => 6,
								'confirm_time' => date('Y-m-d H:i:s'),
							]);
							// 提交事务
							Db::commit();
							$output->writeln("App".$appId."订单号".$resultArr['out_trade_no']."-退款成功");
						} catch (\Exception $e) {
							// 回滚事务
							Db::rollback();
							$status = 1;
							$output->writeln("退款记录创建失败");
						}
					}else{
						$status = 1;
						$output->writeln("App".$appId."订单号".$resultArr['out_trade_no']."-退款失败");
					}
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