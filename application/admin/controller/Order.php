<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Marker;
use app\admin\model\OrderRefund;
class Order extends Base
{
    public function index()
    {

        $orderStatus = Request::instance()->param('orderStatus', -1, 'intval');
        $keyword = Request::instance()->param('keyword', '');

        $where = [];
        if($orderStatus > -1) {
            $where['order_status'] = $orderStatus;
        }
        if ($keyword) {
            $where['order_sn'] = $keyword;
        }

        $list = \app\admin\model\Order::where($where)->order(['id'=>'desc'])->paginate(10, false, ['query' => ['orderStatus' => $orderStatus, 'order_sn' => $keyword]]);
        $orderStatusList = \app\admin\model\Order::getOrderStatusAttr();

        // $orderList = $list->items();
        // foreach ($orderList as $key => $val) {
        //     $orderList[$key] = $val;
        // }
        $total = $list->total();
        $page = $list->render();

        $this->assign('orderList', $list);
        $this->assign('orderStatusList', $orderStatusList);
        $this->assign('orderStatus', $orderStatus);
        $this->assign('keyword', $keyword);
        $this->assign('page', $page);
        $this->assign('total', $total);
        $this->assign('title','订单管理');
        return $this->fetch();
    }

    public function detail($orderSn)
    {
        $model = \app\admin\model\Order::where([
            'order_sn' => $orderSn
        ])->find();
        if (!$model) {
            echo '订单不存在';
            exit;
        }        
        $express = \app\admin\model\Order::getDeliveryTypeAttr($model->express);
        $orderRefund = [];
        if ($model->payment_status == 2 || $model->payment_status == 3) {
            $orderRefund = OrderRefund::where(['order_sn' => $model->order_sn])->find();
        }
        if ($model->express == 1) {
            $company = \app\admin\model\Company::where(['id' => $model->address])->find();
        } else {
            $company = [];
        }

        $marker = Marker::with('rent')->where(['id' => $model['marker_id']])->find()->toArray();
        $this->assign('title', '订单详情');
        $this->assign('marker', $marker);
        $this->assign('refund', $orderRefund);
        $this->assign('express', $express);
        $this->assign('company', $company);
        $this->assign('model', $model);
        return $this->fetch();
    }

    public function editMemo()
    {
        $expressNumber = Request::instance()->param('expressNumber', '');
        $orderSn = Request::instance()->param('orderSn', '');

        $appId = $this->appid;
        $model = \app\admin\model\Order::where([
            'order_sn' => $orderSn
        ])->find();
        if (!$model) {
            return ['error' => 1, 'msg' => '订单不存在'];
        }

        $model->express_number = $expressNumber;
        $model->order_status = 2;
        $model->confirm_time = date('Y-m-d H:i:s');
        $model->send_time    = date('Y-m-d H:i:s');
        $model->save();
        return ['error' => 0, 'msg' => '成功'];
    }

    public function ajaxDealOrderStatus()
    {
        $request = Request::instance();
        $orderSn = $request->param('orderSn', '');
        $status = $request->param('status', 0);


        $where = array(
            'order_sn' => $orderSn,
        );
        \app\admin\model\Order::where($where)->update([
            'order_status' => $status,
            'confirm_time' => date('Y-m-d H:i:s')
        ]);

        return ['error' => 0, 'msg' => '成功'];
    }
}
