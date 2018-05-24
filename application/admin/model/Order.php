<?php

namespace app\admin\model;

use think\Model;

class Order extends Model
{
    //订单状态 1已支付，2已发货，3已归还，4申请退款，5已退款
    const ORDER_STATUS_UNPAY = 0;
    const ORDER_STATUS_PAID = 1;
    const ORDER_STATUS_SHIPPED = 2;
    const ORDER_STATUS_FINISHED = 3;
    const ORDER_STATUS_APPLYREFUND = 4;
    const ORDER_STATUS_REFUND = 5;

    // const GOODS_TYPE_ARTICLE = 'Article';
    // const GOODS_TYPE_VIDEO = 'Video';
    // const GOODS_TYPE_GOODS = 'Goods';
    // const GOODS_TYPE_POSTER = 'Poster';

    const DELIVERY_TYPE_TAKE = 1;
    const DELIVERY_TYPE_EXPRESS = 2;
    const DELIVERY_TYPE_FLASH = 3;
    const DELIVERY_TYPE_DEFAULT = 0;

    public function user()
    {
        return $this->hasOne('User', 'uid', 'uid');
    }

    public function sort() {
        return $this->hasOne('sort', 'id', 'sort_id');
    }

    public function attr() {
        return $this->hasOne('attribute', 'id', 'attr_id');
    }

    public function marker() {
        return $this->hasOne('marker', 'id', 'marker_id');
    }

    public static function getOrderStatusAttr($value = null)
    {
        $status = [
            self::ORDER_STATUS_UNPAY => '<span style="color: grey">待付款</span>',
            // self::ORDER_STATUS_GROUP => '<span style="color: Blue;">待成团</span>',
            self::ORDER_STATUS_PAID => '<span style="color: Violet">待发货</span>',
            self::ORDER_STATUS_SHIPPED => '<span style="color: Green">待收货</span>',
            self::ORDER_STATUS_FINISHED => '<span style="color: Orange">已完成</span>',
            self::ORDER_STATUS_APPLYREFUND => '<span style="color: Red">申请退款</span>',
            self::ORDER_STATUS_REFUND => '<span style="color: #000;">已退款</span>'
        ];
        if ($value !== null) {
            return $status[$value];
        }
        return $status;
    }

    public static function getGoodsTypeAttr($value = null)
    {
        $status = [
            self::GOODS_TYPE_ARTICLE => '图文',
//            self::GOODS_TYPE_VIDEO => '视频',
            self::GOODS_TYPE_GOODS => '商品',
            self::GOODS_TYPE_POSTER => '活动',
        ];
        if ($value !== null) {
            return $status[$value];
        }
        return $status;
    }

    public static function getDeliveryTypeAttr($value = null)
    {
        $status = [
            self::DELIVERY_TYPE_EXPRESS => '配送',
            self::DELIVERY_TYPE_TAKE => '自提',
            self::DELIVERY_TYPE_FLASH => '闪送',
            self::DELIVERY_TYPE_DEFAULT => '默认',
        ];

        if ($value !== null) {
            return $status[$value];
        }
        return $status;
    }
}
