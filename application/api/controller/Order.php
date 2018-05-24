<?php

namespace app\api\controller;

use app\api\model\PaymentLog;
use app\api\model\RefundLog;
use app\api\model\UserAccountLog;
use app\api\model\UserSession;
use library\Helper;
use library\WxTemplateMsg;
use think\Cache;
use think\Config;
use think\Controller;
use think\Db;
use think\Log;
use think\Request;
use wxpay\Wxpay;
use app\api\model\Goods;
use app\api\model\UserCoupon;
use app\api\model\User;
use app\api\model\Order as Od;
use app\api\controller\Goods as G;
use app\api\model\Car;
use app\api\model\OrderRefund;
use app\api\model\Address;
use app\api\model\Setting;
use app\api\model\Company;
class Order extends Controller
{
   
    public function save(Request $request)
    {
        $userSession = $request->param('userSession', '');
        $moneyAmount = $request->param('moneyAmount', '');
        $payAmount   = $request->param('payAmount', '');
        $deAmount    = $request->param('deAmount', 0);
        $goodsId     = $request->param('goodsId', '');
        $couponId    = $request->param('couponId', 0);
        $express     = $request->param('express', '');
        $address     = $request->param('addressId', '');
        $startTime   = $request->param('startTime', '');
        $endTime     = $request->param('endTime', '');
        $days        = $request->param('days', '');

		if (!$goodsId || !$userSession || !$express || !$address || !$startTime || !$endTime) {
			return ['code' => 1,'msg' => '参数错误'];
		}
        
		$userData = UserSession::getInfo($userSession);
		if (!$userData) {
			return ['code' => 1, 'msg' => '用户不存在'];
		}
		$uid = $userData['userInfo']['uid'];
        $user = User::where(['uid' => $uid])->find();
        
        if ($express == 1) {
            $addressInfo = Company::getInfo($address);
        } else {
            $addressInfo = Address::getInfo($address, $uid);
            
        }
        if (!$addressInfo) {
            return ['code' => 1, 'msg' => '地址信息有误'];
        }

        if ($deAmount > $user['de_amount']) {
            return ['code' => 1, 'msg' => '抵扣金额错误'];
        }

        $moneyAmount = sprintf('%.2f', $moneyAmount);
        $payAmount   = sprintf('%.2f', $payAmount);

        $deAmount    = $deAmount ? sprintf('%.2f', $deAmount) : 0;

		if (!$moneyAmount || !$payAmount) {
			return ['code' => 1,'msg' => '金额不正确'];
		}

		$goods = Goods::where(['id' => $goodsId, 'status' => 1])->find();
		if (!$goods) {
            return ['code' => 1,'msg' => '商品不存在'];
        }

        if (($goods['inventory'] - 1) < 0) {
            return ['code' => 1, 'msg' => '库存不足'];
        }

        if ($couponId != 0) {
            $coupon = UserCoupon::where(['coupon_id' => $couponId])->find();
            $cp = $coupon['coupon_price'];    
        } else {
            $cp = 0; 
        }

		$openId = $userData['openId'];
        $username = $userData['userInfo']['username'];
        $avatar = $userData['userInfo']['avatar'];
        $nonceStr = Helper::createRandomStr(32);
        $ip = Request::instance()->ip();
        $orderSn = date('YmdHis').substr(microtime(),2,5).rand(100000, 999999);
        Db::startTrans();
        try{
            if ($express == 1) {
                $data = [
                    'order_sn' => $orderSn,
                    'uid' => $uid,
                    'openid' => $openId,
                    'username' => $username,
                    'avatar' => $avatar,
                    'money_amount' => $moneyAmount,
                    'pay_amount'   => $payAmount,
                    'de_amount'    => $deAmount,
                    'goods_id' => $goodsId,
                    'coupon'   => $couponId,
                    'coupon_amount' => $cp,
                    'address'       => $address,
                    'start_time'    => $startTime,
                    'end_time'      => $endTime,
                    'sort_id'       => $goods['sort_id'],
                    'attr_id'       => $goods['attr_id'],
                    'marker_id'     => $goods['marker_id'],
                    'days'          => $days,
                    'express'       => $express,
                    'confirm_time'  => date('Y-m-d H:i:s'),
                ];
            } else {
                $data = [
                    'order_sn' => $orderSn,
                    'uid' => $uid,
                    'openid' => $openId,
                    'username' => $username,
                    'avatar' => $avatar,
                    'money_amount' => $moneyAmount,
                    'pay_amount'   => $payAmount,
                    'de_amount'    => $deAmount,
                    'goods_id' => $goodsId,
                    'coupon'   => $couponId,
                    'coupon_amount' => $cp,
                    'address'       => $address,
                    'start_time'    => $startTime,
                    'end_time'      => $endTime,
                    'sort_id'       => $goods['sort_id'],
                    'attr_id'       => $goods['attr_id'],
                    'marker_id'     => $goods['marker_id'],
                    'days'          => $days,
                    'express'       => $express,
                    'confirm_time'  => date('Y-m-d H:i:s'),
                    'buyer_name'    => $addressInfo['uname'],
                    'buyer_mobile'  => $addressInfo['mobile'],
                    'buyer_city'    => $addressInfo['city'],
                    'buyer_address' => $addressInfo['address'],
                ];
            }
            
            $order = \app\api\model\Order::create($data);
            
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ['code' => 1,'msg' => '创建订单错误'];
        }
        // 下单支付
        $params = [
            'nonce_str' => $nonceStr,
            'body' => '大猫',
            'out_trade_no' => $orderSn,
            'total_fee' => $payAmount,
            'spbill_create_ip' => $ip,
            'notify_url' => $this->getDomainHost() . '/api/order/wxappNotify',
            'trade_type' => 'JSAPI',
            'openid' => $openId,
        ];
        $wxpay = new Wxpay();
        $resultArr = $wxpay->unifiedorder($params);
        //Log::record($resultArr);
        if ($resultArr['return_code'] == 'SUCCESS' && isset($resultArr['result_code']) && $resultArr['result_code'] == 'SUCCESS') {
            $order->prepay_id = $resultArr['prepay_id'];
            $order->save();

            $data = [
                'appId' => $resultArr['appid'],
                'timeStamp' => time(),
                'nonceStr' => $nonceStr,
                'package' => 'prepay_id='.$resultArr['prepay_id'],
                'signType' => 'MD5'
            ];
            $paySign = $wxpay->generateSign($data);
            $data['paySign'] = $paySign;
            $data['orderSn'] = $orderSn;
        } else {
            return ['code' => 1, 'msg' => $resultArr['return_msg']];
        }

        return [
            'code' => 0,
            'msg' => '成功',
            'data' => $data
        ];
    }

    private function getDomainHost()
    {
        $domainHost = $_SERVER['HTTP_HOST'];
        if ($domainHost == 'wxapp.feeyan.com') {
            return 'https://wxapp.feeyan.com/app/photo';
        } elseif ($domainHost == 'debug.oa.feeyan.com') {
            return 'https://debug.oa.feeyan.com/photo';
        }
    }

    private function getNotifyData()
    {
        $params = file_get_contents("php://input");
        if($params == null) {
            $params = $GLOBALS['HTTP_RAW_POST_DATA'];
        }
        return $params;
    }

    /**
     * 支付回调
     * @return array
     */
    public function wxappNotify()
    {
        $params = $this->getNotifyData();
        if ($params) {
            $data = xmlToArray($params);
            if ($data['return_code'] == 'SUCCESS') {
                $sign = $data['sign'];
                unset($data['sign']);

                $orderSn = $data['out_trade_no'];
                $order = \app\api\model\Order::where([
                    'order_sn' => $orderSn
                ])->find();
                
                $rate = Setting::where(['type' => 5])->value('value');
                $user = User::where(['uid' => $order['uid']])->find();
                if (!$order) {
                    Log::record('===========订单错误');
                    return ['code' => 1, 'msg' => '订单不存在'];
                }

                $paymentLog = PaymentLog::where([
                    'order_sn' => $orderSn
                ])->find();
                if (!$paymentLog) {
                    $paymentLog = PaymentLog::create([
                        'order_sn' => $data['out_trade_no'],
                        'transaction_id' => $data['transaction_id'],
                        'payment_result' => json_encode($data)
                    ]);
                }
                $profitUser = '';
                if ($user['profit_user']) {
                    $profitUser = User::where(['uid' => $user['profit_user']])->find();
                }

                $wxpay = new Wxpay();
                $signTemp = $wxpay->generateSign($data);
                if ($signTemp == $sign) {
                    if ($order['payment_status'] == 1) {
                        $this->returnSuccess();
                    } else {
                        Db::startTrans();

                        try{

                            if ($profitUser) {
                                $money = $order['pay_amount'] * $rate;
                                User::where(['uid' => $user['profit_user']])->setInc('profit_amount', $money);
                            }
                            

                            //更新订单状态
                            $order->order_status = 1;
                            $order->payment_status = 1;
                            $order->confirm_time = date('Y-m-d H:i:s');
                            $order->payment_time = date('Y-m-d H:i:s');
                            $order->save();
							
                            //更新流水状态
                            $paymentLog->status = 1;
                            $paymentLog->save();

                            UserCoupon::where(['uid' => $order['uid'], 'coupon_id' => $order['coupon']])->save(['is_used' => 2, 'used_time' => time()]);
                            User::where(['uid' => $user['uid']])->setDec('de_amount', $order['de_amount']);
                            User::where(['uid' => $user['uid']])->setInc('de_amount', $order['pay_amount']);
                            Goods::where(['id' => $order['goods_id']])->setDec('inventory');
                            // 提交事务
                            Db::commit();

                            //推送消息
//                            $templateId = 'Gm4UuNEEb7-Ad5kP2pbW4sZhCQTCoFe6u5Zg0to8ono';
//                            $params = [
//                                'touser' => $order['friendInfo']['openid'],
//                                'template_id' => $templateId,
//                                'page' => 'pages/show/index?id='.$order['friend_id'],
//                                'form_id' => $order['friendInfo']['form_id'],
//                                'data' => [
//                                    'keyword1' => [
//                                        'value' => $order['username'],
//                                    ],
//                                    'keyword2' => [
//                                        'value' => '¥ '.$order->money_amount,
//                                    ],
//                                    'keyword3' => [
//                                        'value' => $good['realname'],
//                                    ],
//                                    'keyword4' => [
//                                        'value' => date('Y-m-d H:i'),
//                                    ],
//                                    'keyword5' => [
//                                        'value' => '',
//                                    ],
//                                ]
//                            ];
//                            WxTemplateMsg::send($params);

                            //返回成功状态
                            $this->returnSuccess();
                        } catch (\Exception $e) {
                            // 回滚事务
                            Db::rollback();
                        }
                    }
                } else {
                    Log::record('===========签名错误');
                    return ['code' => 1, 'msg' => '签名错误'];
                }
            }
        } else {
            return ['code' => 1, 'msg' => '参数错误'];
        }
    }

    private function returnSuccess()
    {
        $return['return_code'] = 'SUCCESS';
        $return['return_msg'] = 'OK';
        $xml = arrayToXml($return);
        echo $xml;
        exit;
    }


    public function addCar(Request $request) {
        $userSession = $request->param('userSession', '');
        
        $gid         = $request->param('goodsId', '');
        $startTime   = $request->param('startTime', '');
        $endTime     = $request->param('endTime', '');
        $days        = $request->param('days', '');
        $userData = UserSession::getInfo($userSession);

        if (!$userData) {
            return ['code' => 1, 'msg' => '没有用户信息'];
        }

        $info = Goods::where(['id' => $gid, 'status' => 1])->find();
        
        if (!$info) {
            return ['code' => 1, 'msg' => '商品信息错误'];
        }
        if (($info['inventory'] - 1) < 0) {
            return ['code' => 1, 'msg' => '商品已租完'];
        }
        // $moneyAmount = sprintf('%.2f', $moneyAmount);
		if (!$days) {
			return ['code' => 1,'msg' => '天数不正确'];
        }

        if (!$startTime || !$endTime) {
            return ['code' => 1, 'msg' => '请选择时间'];
        }
        $orderSn = date('YmdHis').substr(microtime(),2,5).rand(100000, 999999);
        $data = [
            'goods_id'     => $info['id'],
            'sort_id'      => $info['sort_id'],
            'attr_id'      => $info['attr_id'],
            'marker_id'    => $info['marker_id'],
            'days'         => $days,
            'confirm_time' => date('Y-m-d H:i:s'),
            'openid'       => $userData['openId'],
            'username'     => $userData['userInfo']['username'],
            'avatar'       => $userData['userInfo']['avatar'],
            'uid'          => $userData['userInfo']['uid'],
            'order_sn'     => $orderSn,
            'is_car'       => 1,
            'start_time'   => $startTime,
            'end_time'     => $endTime,
        ];
        Db::startTrans();
        try{
            $order = \app\api\model\Order::create($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ['code' => 1,'msg' => '添加购物车失败'];
        }

        return ['code' => 0, 'msg' => '添加成功'];
    } 



    public function carPay(Request $request) {
        $userSession = $request->param('userSession', '');
        $orders    = $request->param('orders', '');
        $amount    = $request->param('amount', '');
        $express   = $request->param('express', '');
        $address   = $request->param('addressId', '');
        $deAmount  = $request->param('deAmount', '');

        $userData = UserSession::getInfo($userSession);

        if (!$express || !$address) {
            Log::record('==========xingxi');
            
            return ['code' => 1, 'msg' => '参数错误'];
        }
        if (!$userData) {
            return ['code' => 1, 'msg' => '没有用户信息'];
        }
        $uid = $userData['userInfo']['uid'];

        if ($express == 1) {
            $addressInfo = Company::getInfo($address);
        } else {
            $addressInfo = Address::getInfo($address, $uid);
            
        }

        if (!$addressInfo) {
            return ['code' => 1, 'msg' => '地址信息有误'];
        }
        
        if (!sprintf('%.2f', $amount)) {
            return ['code' => 1, 'msg' => '金额错误'];
        }
        $deAmount = $deAmount ? sprintf('%.2f', $deAmount) : 0;

        $openId = $userData['openId'];
        $nonceStr = Helper::createRandomStr(32);
        $ip = Request::instance()->ip();
        $carSn = date('YmdHis').substr(microtime(),2,5).rand(100000, 999999);

        $orders = json_decode($orders, true);
        foreach ($orders as $k => $v) {
            if (!$v['startTime'] || !$v['endTime'] || !$v['order_sn'] || !$v['days']) {
                Log::record('==========订单');
                return ['code' => 1, 'msg' => '参数错误'];
            }

            if (!sprintf('%.2f', $v['payAmount'] )|| !sprintf('%.2f', $v['moneyAmount'])) {
                return ['code' => 1, 'msg' => '金额错误'];
            }
            $order = Od::where(['uid' => $uid, 'order_sn' => $v['order_sn']])->find();
            if (!$order) {
                return ['code' => 1, 'msg' => '订单错误'];
            }
            if ($express == 1) {
                $cData[$k] = [
                    'order_sn'      => $v['order_sn'],
                    'money_amount'  => $v['moneyAmount'],
                    'pay_amount'    => $v['payAmount'],
                    'address'       => $address,
                    'start_time'    => $v['startTime'],
                    'end_time'      => $v['endTime'],
                    'days'          => $v['days'],
                    'express'       => $express,
                    'car_sn'        => $carSn,
                    'confirm_time'  => date('Y-m-d H:i:s'),
                ];
            } else {
                $cData[$k] = [
                    'order_sn'      => $v['order_sn'],
                    'money_amount'  => $v['moneyAmount'],
                    'pay_amount'    => $v['payAmount'],
                    'address'       => $address,
                    'start_time'    => $v['startTime'],
                    'end_time'      => $v['endTime'],
                    'days'          => $v['days'],
                    'express'       => $express,
                    'car_sn'        => $carSn,
                    'confirm_time'  => date('Y-m-d H:i:s'),
                    'buyer_name'    => $addressInfo['uname'],
                    'buyer_mobile'  => $addressInfo['mobile'],
                    'buyer_city'    => $addressInfo['city'],
                    'buyer_address' => $addressInfo['address'],
                ];
            }
            
        }
        
       
        Db::startTrans();
        try{
            
            $car = Car::create([
                'car_sn'        => $carSn,
                'uid'           => $uid,
                'openid'        => $openId,   
                'amount'        => $amount,
                'de_amount'     => $deAmount,
                'address'       => $address,
                'express'       => $express,
            ]);

            foreach($cData as $k=>$v) {
                $orders = Od::where(['uid' => $uid, 'order_sn' => $v['order_sn']])->update([
                                        'money_amount'  => $v['money_amount'],
                                        'pay_amount'    => $v['pay_amount'],
                                        'address'       => $address,
                                        'start_time'    => $v['start_time'],
                                        'end_time'      => $v['end_time'],
                                        'days'          => $v['days'],
                                        'express'       => $express,
                                        'car_sn'        => $carSn,
                                        'confirm_time'  => date('Y-m-d H:i:s'),
                                        'buyer_name'    => isset($v['buyer_name']) ? $v['buyer_name'] : '',
                                        'buyer_mobile'  => isset($v['buyer_mobile']) ? $v['buyer_mobile'] : '',
                                        'buyer_city'    => isset($v['buyer_city']) ? $v['buyer_city'] : '',
                                        'buyer_address' => isset($v['buyer_address']) ? $v['buyer_address'] : '',
                                    ]);
            }
            
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ['code' => 1,'msg' => '创建订单错误'];
        }

        // 下单支付
        $params = [
            'nonce_str' => $nonceStr,
            'body' => '大猫',
            'out_trade_no' => $carSn,
            'total_fee' => $amount,
            'spbill_create_ip' => $ip,
            'notify_url' => $this->getDomainHost() . '/api/order/carWxappNotify',
            'trade_type' => 'JSAPI',
            'openid' => $openId,
        ];
        $wxpay = new Wxpay();
        $resultArr = $wxpay->unifiedorder($params);
        //Log::record($resultArr);
        if ($resultArr['return_code'] == 'SUCCESS' && isset($resultArr['result_code']) && $resultArr['result_code'] == 'SUCCESS') {
            // $order->prepay_id = $resultArr['prepay_id'];
            // $order->save();

            Od::where(['car_sn' => $carSn])->update(['prepay_id' => $resultArr['prepay_id']]);
            $data = [
                'appId' => $resultArr['appid'],
                'timeStamp' => time(),
                'nonceStr' => $nonceStr,
                'package' => 'prepay_id='.$resultArr['prepay_id'],
                'signType' => 'MD5'
            ];
            $paySign = $wxpay->generateSign($data);
            $data['paySign'] = $paySign;
            $data['orderSn'] = $carSn;
        } else {
            return ['code' => 1, 'msg' => $resultArr['return_msg']];
        }

        return [
            'code' => 0,
            'msg' => '成功',
            'data' => $data
        ];


    }

    /**
     * 支付回调
     * @return array
     */
    public function carWxappNotify()
    {
        $params = $this->getNotifyData();
        if ($params) {
            $data = xmlToArray($params);
            if ($data['return_code'] == 'SUCCESS') {
                $sign = $data['sign'];
                unset($data['sign']);

                $carSn = $data['out_trade_no'];
                $car = Car::where([
                    'car_sn' => $carSn
                ])->find();
                
                $user = User::where(['uid' => $car['uid']])->find();
                if (!$car) {
                    Log::record('===========订单错误');
                    return ['code' => 1, 'msg' => '订单不存在'];
                }

                $paymentLog = PaymentLog::where([
                    'car_sn' => $carSn
                ])->find();
                if (!$paymentLog) {
                    $paymentLog = PaymentLog::create([
                        'order_sn' => $data['out_trade_no'],
                        'transaction_id' => $data['transaction_id'],
                        'payment_result' => json_encode($data),
                        'type' => 1,
                    ]);
                }

                $orders = Od::field('id, goods_id')->where(['car_sn' => $carSn, 'is_car' => 1])->select()->toArray();
                $payMoney = Od::where(['car_sn' => $carSn, 'is_car' => 1])->sum('pay_amount');
                $rate = Setting::where(['type' => 5])->value('value');

                $profitUser = '';
                if ($user['profit_user']) {
                    $profitUser = User::where(['uid' => $user['profit_user']])->find();
                }

                $wxpay = new Wxpay();
                $signTemp = $wxpay->generateSign($data);
                if ($signTemp == $sign) {
                    if ($car['payment_status'] == 1) {
                        $this->returnSuccess();
                    } else {
                        Db::startTrans();
                        try{
                            //更新订单状态
                            $time = date('Y-m-d H:i:s');
                            $car->order_status = 1;
                            $car->payment_status = 1;
                            $car->confirm_time = $time;
                            $car->payment_time = $time;
                            $car->save();
                            $t = time();
                            foreach($orders as $k=>$v){
                                Od::save(['order_status' => 1, 'payment_status' => 1, 'payment_time' => $time], ['id' =>$v['id'], 'uid' => $car['uid']]);
                                Goods::where(['id' => $v['goods_id']])->setDec('inventory');
                                if ($v['coupon']) {
                                    UserCoupon::where(['uid' => $car['uid'], 'coupon_id' => $v['coupon']])->save(['is_used' => 2, 'used_time' => $t]);     
                                }
                            }
                            //更新流水状态
                            $paymentLog->status = 1;
                            $paymentLog->save();
                            
                            User::where(['uid' => $user['id']])->setDec('de_amount', $car['amount']);
                            User::where(['uid' => $user['id']])->setInc('de_amount', $car['amount']);
                            if ($profitUser) {
                                $money = $payMoney * $rate;
                                User::where(['openid' => $user['profit_user']])->setInc('profit_amount', $money);
                            }
                            // 提交事务
                            Db::commit();

                            //返回成功状态
                            $this->returnSuccess();
                        } catch (\Exception $e) {
                            // 回滚事务
                            Db::rollback();
                        }
                    }
                } else {
                    Log::record('===========签名错误');
                    return ['code' => 1, 'msg' => '签名错误'];
                }
            }
        } else {
            return ['code' => 1, 'msg' => '参数错误'];
        }
    }

    public function carList(Request $request) {
        $userSession = $request->param('userSession', '');
        $userData = UserSession::getInfo($userSession);

        if (!$userData) {
            return ['code' => 1, 'msg' => '没有用户信息'];
        }

        $uid = $userData['userInfo']['uid'];
        $where = [
            'uid' => $uid,
            'is_car' => 1,
            'payment_status' => 0,
            'order_status' => 0,
        ];
        $list = Od::alias('o')->where($where)->field('o.*, g.pics, g.parts, s.name, a.attr_name, m.marker_name')
                ->join('photo_goods g', 'g.id=o.goods_id')
                ->join('photo_sort s','s.id=o.sort_id', 'left')
                ->join('photo_attribute a', 'a.id=o.attr_id', 'left')
                ->join('photo_marker m', 'm.id=o.marker_id', 'left')
                ->order('o.confirm_time desc')->select()->toArray();
        foreach ($list as $k=>$v) {
            $rentTag = G::getRentTag($v['marker_id'], $uid);
            $list[$k]['pics'] = json_decode($v['pics'], true);
            $list[$k]['rent'] = $rentTag['rent'];
            $list[$k]['tags'] = $rentTag['tags'];
            $list[$k]['coupon'] = $rentTag['coupon'];
            $list[$k]['start_time'] = date('Y-m-d', strtotime($v['start_time']));
            $list[$k]['end_time']   = date('Y-m-d', strtotime($v['end_time']));
            $list[$k]['check'] = 1;
        }
        
        if (!$list) {
            return ['code' => 0, 'msg' => '还未添加商品', 'data' => ['list' => []]];
        } else {
            return ['code' => 0, 'msg' => '成功', 'data' => ['list' => $list]];
        }

    }

    public function orderList(Request $request) {
        $userSession = $request->param('userSession', '');
        
        $userData = UserSession::getInfo($userSession);
        if (!$userData) {
            return ['code' => 1, 'msg' => '用户不存在'];
        }
        $uid = $userData['userInfo']['uid'];

        $page = $request->param('page', 1);
        $type = $request->param('type', '');

        if ($type < 0 || $type >6) {
            return ['code' => 1, 'msg' => '参数错误'];
        }
        switch ($type) {
            case 1: //未支付
                $where = [
                    'order_status' => 0,
                    'uid'          => $uid,
                    'del_status'   => 1,
                ];
                $status_name = '未支付';
                break;
            case 2://待发货
                $where = [
                    'order_status'   => 1,
                    'uid'            => $uid,
                    'del_status'     => 1,
                    'payment_status' => 1,
                    
                ];
                $status_name = '待发货';
                
                break;
            case 3: //待收货
                $where = [
                    'order_status'   => 2,
                    'uid'            => $uid,
                    'del_status'     => 1,
                    'payment_status' => 1,
                    'send_time'      => ['exp', " < date_add(send_time,interval 3 day)"],
                ];
                $status_name = '待收货';                
                break;
            case 4: //待归还
                $where = [
                    'order_status'   => 2,
                    'uid'            => $uid,
                    'del_status'     => 1,
                    'payment_status' => 1,
                    'end_time'       => ['exp', " < date_add(end_time,interval 3 day)"],
                    'start_time'     => ['exp', " < now()"],
                ];
                break;
                $status_name = '待归还';
                
            case 5: //已归还
                $where = [
                    'order_status'   => 3,
                    'uid'            => $uid,
                    'del_status'     => 1,
                    'payment_status' => 1,
                    
                ];
                $status_name = '已归还';
                
                break;
            
            case 6: //退订
                $where = [
                    'order_status'   => 4,
                    'uid'            => $uid,
                    'del_status'     => 1,
                    'payment_status' => 2,    
                ];
                $status_name = '退订中';
                break;
            
                
            case 0:
                $where = [
                    'order_status'   => ['>', 0],
                    'uid'            => $uid,
                    'del_status'     => 1,
                    'payment_status' => ['>', 0],
                    
                ];
                
                break;
        }

        $pageSize = Config::get('paginate.list_rows');
        $start = ($page - 1) * $pageSize;
        $count =  Od::with('sort,attr,marker,goods')->select()->count();
        $pageTotal = ceil($count / $pageSize);

        $list = Od::with('sort,attr,marker,goods,address')->where($where)->limit($start, $pageSize)->select()->toArray();

        if (!$list) {
            return ['code' => 1, 'msg' => '没有订单信息'];
        }

        foreach ($list as $k=>$v){
            $rentTag = G::getRentTag($v['marker_id']);

            $pics = json_decode($v['goods']['pics'], true);
            $list[$k]['pics'] = $pics;
            $status_name = self::getStatusName($v['order_status'], ['send_time' => $v['send_time'], 'start_time' => $v['start_time'], 'end_time' => $v['end_time']]);
            $list[$k]['rent'] = $rentTag['rent'];
            $list[$k]['tags'] = $rentTag['tags'];
            $list[$k]['status_name'] = $status_name;

        }
        
        $rtData = [
            'pageTotal' => $pageTotal,
            'page'      => $page,
            'size'      => $pageSize,
            'list'      => $list,
        ];

        return ['code' => 0, 'msg' => '成功', 'data' => $rtData];
    }

    static private function getStatusName($status, $time=[]) {
        switch($status) {
            case 0:
                $name = '未支付';
            break;
            case 1:
                $name = '待发货';
            break;
            case 2:
                if (time() < strtotime('+3 day', strtotime($time['send_time']))){
                    $name = '待收货';
                } elseif (time() > strtotime($time['start_time']) && time() < strtotime('+3 day', strtotime($time['end_time']))) {
                    $name = '待归还';
                }
            break;
            case 3:
                $name = '已归还';
                break;
            case 4:
                $name = '退订中';
                break;
            case 5:
                $name = '已退款';
                break;
        }
        return $name;
    }

    public function applyRefund(Request $request) {
        $userSession = $request->param('userSession', '');
        
        $userData = UserSession::getInfo($userSession);
        if (!$userData) {
            return ['code' => 1, 'msg' => '用户不存在'];
        }
        $uid = $userData['userInfo']['uid'];
        
        $reason = $request->param('reason', '');
        $memo   = $request->param('memo', '');

        if (!$reason) {
            return ['code' => 1, 'msg' => '请填写退款原因'];
        }

        $orderSn = $request->param('order_sn', '');

        $info = Od::where(['payment_status' => 1, 'order_status' => 1, 'uid' => $uid, 'order_sn' => $orderSn])->find();

        if ($info['payment_status'] == 0) {
            return ['code' => 1, 'msg' => '订单未付款'];
        }

        if ($info['payment_status'] == 1 && $info['order_status'] != 1) {
            return ['code' => 1, 'msg' => '订单已发货'];
        }

        $data = [
            'order_status'   => 4,
            'payment_status' => 2,
        ];

        if ($info['is_car'] == 1) {
            $order_sn = $info['car_sn']; 
        } else {
            $order_sn = $info['order_sn'];
        }

        $rData = [
            'order_sn'   => $order_sn,
            'uid'        => $uid,
            'total_fee'  => $info['pay_amount'],
            'refund_fee' => $info['pay_amount'],
            'reason'     => $reason,
            'memo'       => $memo,
        ];

        $id = OrderRefund::insertGetId($rData);

        $rt = $info->save($data);

        if ($rt !== false && $id) {
            return ['code' => 0, 'msg' => '提交成功'];
        } else {
            return ['code' => 1, 'msg' => '提交申请失败'];
        }
    }

}
