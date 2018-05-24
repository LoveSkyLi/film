<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Config;
use think\Log;
use think\Cache;
use library\Helper;
use app\api\model\UserSession;
use app\api\model\Sort;
use app\api\model\Attribute;
use app\api\model\Marker;
use app\api\model\Address;
use app\api\model\Goods as Gd;
use app\api\model\GoodsList;
use app\api\model\Rent;
use app\api\model\Tag;
use app\api\model\UserCoupon;
use think\Db;

class Goods extends Controller
{
    public function getGoodsLists(Request $request) {
        
        $userSession = $request->param('userSession', '');
        $sortId      = $request->param('sortId', 0);
        $attrId      = $request->param('attrId', 0);
        $page        = $request->param('page', 1);

        if ($sortId == 0 && $attrId == 0) {
            $where = [
                'photo_goods.status' => 1,
            ];
        } elseif ($sortId != 0 && $attrId == 0) {
            $where = [
                'photo_goods.sort_id' => $sortId,
                'photo_goods.status'  => 1,
            ];
        } elseif ($sortId == 0 && $attrId != 0) {
            $where = [
                'photo_goods.attr_id' => $attrId,
                'photo_goods.status'  => 1,
            ];
        } elseif ($sortId != 0 && $attrId != 0) {
            $where = [
                'photo_goods.sort_id' => $sortId,
                'photo_goods.attr_id' => $attrId,
                'photo_goods.status'  => 1,
            ];
        }
        
        $pageSize = Config::get('paginate.list_rows');
        $start = ($page - 1) * $pageSize;
        $count = Gd::alias('g')->where($where)
                    ->join('photo_sort s','s.id=g.sort_id', 'left')
                    ->join('photo_attribute a', 'a.id=g.attr_id', 'left')
                    ->join('photo_marker m', 'm.id=g.marker_id', 'left')
                    ->count();

        $pageTotal = ceil($count / $pageSize);
        $list = Gd::field('g.id, g.cover, g.marker_id, s.name, a.attr_name, m.marker_name')->alias('g')->where($where)
                    ->join('photo_sort s','s.id=g.sort_id', 'left')
                    ->join('photo_attribute a', 'a.id=g.attr_id', 'left')
                    ->join('photo_marker m', 'm.id=g.marker_id', 'left')
                    ->order('g.displayorder asc, g.id desc')->page($start, $pageSize)->select()->toArray();
        
        if (!$list || empty($list)) {
            return ['code' => 1, 'msg' => '暂无器材'];
        } else {
            foreach ($list as $k=>$v) {
                $rt = self::getRentTag($v['marker_id']);
                $list[$k]['rent'] = $rt['rent'];
                $list[$k]['tags'] = $rt['tags'];
                $list[$k]['coupon'] = $rt['coupon'];
            }

            $data = [
                'pageTotal' => $pageTotal,
                'page'      => $page,
                'size'      => $pageSize,
                'list'      => $list,
            ];
            return ['code' => 0, 'msg' => '成功', 'data' => $data];
        }

    }

    public function getGoodsInfo(Request $request) {
        // $userSession = $request->param('userSession', 'd7435731e86c6c0ec411e3a134e8fbb6df6b6e6ecb1848534333484e36a3322d');

        $userSession = $request->param('userSession', '');
        $id = $request->param('id', '');

        $userData = UserSession::getInfo($userSession);
        if (!$userData) {
            return ['code' => 1, 'msg' => '没有用户信息'];

        }
        $uid = $userData['userInfo']['uid'];
        $where = [
            'photo_goods.id' => $id,
            
        ];

        $info = Gd::field('g.*, s.name, a.attr_name, m.marker_name')->alias('g')->where($where)
                    ->join('photo_sort s','s.id=g.sort_id', 'left')
                    ->join('photo_attribute a', 'a.id=g.attr_id', 'left')
                    ->join('photo_marker m', 'm.id=g.marker_id', 'left')
                    ->order('g.displayorder asc')
                    ->find();

        if (!$info) {
            return ['code' => 1, 'msg' => '商品信息错误'];
        }

        $rentTag = self::getRentTag($info['marker_id'], $uid);

        $where = [
            'sort_id'   => $info['sort_id'],
            'attr_id'   => $info['attr_id'],
            'marker_id' => $info['marker_id'],
            'status'    => 1,
        ];
        $info['pics'] = \json_decode($info['pics'], true);
        $info['detail'] = \json_decode($info['detail'], true);
        $count = GoodsList::where($where)->count();
        $info['num']  = $count;
        $info['rent'] = $rentTag['rent'];
        $info['tags']  = $rentTag['tags'];
        $info['coupon'] = $rentTag['coupon'];
        if (!$info) {
            return ['code' => 1, 'msg' => '商品信息错误'];
        } else {
            return ['code' => 0, 'msg' => '成功', 'data' => ['info' => $info]];
        }
        
    }

    static public function getRentTag($id, $uid = 0) {
        $info = Marker::where(['id' => $id])->find();

        $rent = Rent::field('rent, days, renewal, deposit')->where(['id' => $info['rent_id']])->find()->toArray();
        $tags = Tag::field('name')->whereIn('id', $info['tags'])->where(['status' => 1])->select();
        if ($uid != 0) {
            $coupons = UserCoupon::field('coupon_id, coupon_title, coupon_price')->where(['uid' => $uid, 'sort_id' => $info['sort_id'], 'status' => 1])->where('start_time', '<=', date('Y-m-d'))->where('end_time', '>=', date('Y-m-d'))->select()->toArray();

        } else {
            $coupons = [];
        }

        $rent = [
            'rent'    => sprintf('%.0f', $rent['rent']),
            'renewal' => sprintf('%.0f', $rent['renewal']),
            'days'    => $rent['days'],
            'deposit' => sprintf('%.0f', $rent['deposit'])
        ];
        
        

        $data = [
            'rent'   => $rent,
            'tags'   => $tags,
            'coupon' => $coupons,
        ];

        return $data;
    }   

    public function getCouponList(Request $request) {
        $userSession = $request->param('userSession', '');
        $type        = $request->param('type', 'all');
        $userData = UserSession::getInfo($userSession);
        if (!$userData) {
            return ['code' => 1, 'msg' => '没有用户信息'];
        }
        $uid = $userData['userInfo']['uid'];
        if ($type == 'all') {
            $where = [
                'uid' => $uid,
            ];
            $list = UserCoupon::where($where)->select()->toArray();
            
        } elseif ($type == 'order') {
            $where = [
                'uid' => $uid,
                'is_used' => 1,
            ];
            $list = UserCoupon::where($where)
                ->where('start_time', '<=', date('Y-m-d'))
                ->where('end_time', '>=' , date('Y-m-d'))->select()->toArray();

        }
         
        foreach ($list as $k=>$v) {
            if ($v['status'] == 1) {
                $flag = '有效';
            } else {
                $flag = '已失效';
            }
            $p = \sprintf('%.0f', $v['coupon_price']);
            $list[$k]['start_time'] = date('Y-m-d', strtotime($v['start_time']));
            $list[$k]['end_time']   = date('Y-m-d', strtotime($v['end_time']));
            $list[$k]['coupon_price'] = $p;
            $list[$k]['type'] = $flag;
        }
        if (!$list) {
            return ['code' => 1, 'msg' => '没有可使用优惠券'];
        } else {
            return ['code' => 0, 'msg' => '成功', 'data' => ['list' => $list]];
        }
    }


    public function getNavList(Request $request) {
        $userSession = $request->param('userSession', '');
        $userData = UserSession::getInfo($userSession);
        if (!$userData) {
            return ['code' => 1, 'msg' => '没有用户信息'];
        }

        $list = Sort::field('id, name')->where(['status' => 1, 'show' => 1])->select()->toArray();

        foreach ($list as $k=>$v) {
            $attr = Attribute::field('id, attr_name')->where(['status' => 1, 'show' => 1,  'sort_id' => $v['id']])->select()->toArray();
            $list[$k]['attr'] = $attr;
        }
        if (!$list) {
            return ['code' => 1, 'msg' => '请重新加载'];
        }

        return ['code' => 0, 'msg' => '成功', 'data' => ['list' => $list]];
    }

    public function searchGoods(Request $request) {
        $userSession = $request->param('userSession', '');
        
        $userData = UserSession::getInfo($userSession);
        if (!$userData) {
            return ['code' => 1, 'msg' => '用户不存在'];
        }
        $uid = $userData['userInfo']['uid'];

        $keyWords = $request->param('keyWords', '');
        $page     = $request->param('page', 1);

        if (!$keyWords) {
            return ['code' => 1, 'msg' => '请输入关键字'];
        }

        $where = [
            'title'  => ['like', '%' . $keyWords . '%'],
            'status' => 1,
        ];

        $pageSize = Config::get('paginate.list_rows');
        $start = ($page - 1) * $pageSize;
        $count =  Gd::with('sort,attr,marker')->where($where)->count();
        $pageTotal = ceil($count / $pageSize);

        $list = Gd::with('sort,attr,marker')->field('id, sort_id, attr_id, marker_id, pics, detail, parts')->where($where)->page($start, $pageSize)->select()->toArray();

        if (!$list) {
            return ['code' => 1, 'msg' => '没有数据'];
        }

        foreach ($list as $k=>$v) {
            $rt = self::getRentTag($v['marker_id']);
            $list[$k]['rent'] = $rt['rent'];
            $list[$k]['tags'] = $rt['tags'];
            $list[$k]['coupon'] = $rt['coupon'];
            $list[$k]['pics'] = json_decode($v['pics'], true);
            $list[$k]['detail'] = json_decode($v['detail'], true);
        }

        $rtData = [
            'pageTotal'  => $pageTotal,
            'page'       => $page,
            'size'       => $pageSize,
            'list'       => $list,
        ];

        return ['code' => 0, 'msg' => '成功', 'data' => $rtData];


    }
}
