<?php

namespace app\admin\controller;

use app\admin\model\Attribute;
use think\Controller;
use think\Request;
use app\admin\model\Sort;
use app\admin\model\Goods as Gd;
use app\admin\model\GoodsList;
use think\Log;
class Goods extends Base
{
    public function index() {

        $sortId = Request::instance()->param('goodsSort', 0, 'intval');

        if ($sortId) {
            $where['sort_id'] = $sortId;
        }
        $where['status'] = ['>', 0];
        $list = Gd::with('sort,attr,marker')->where($where)->order('displayorder asc, id desc')->paginate();

        $sort = Sort::where(['status' => 1])->order('displayorder asc, id desc')->select();

        $total = $list->total();
        $page = $list->render();

        foreach($list as $k=>$v) {
            $pics = json_decode($v['pics'], true);
            $list[$k]['pics'] = $pics[0];
        }
        $this->assign('total', $total);
        $this->assign('page', $page);
        $this->assign('list', $list);
        $this->assign('sortList', $sort);
        $this->assign('sortId', $sortId);
        $this->assign('title', '商品列表');
        return $this->fetch();
    }

    public function create() {

        $sort = Sort::where(['status' => 1])->order('displayorder asc, id desc')->select();

        $this->assign('sortList', $sort);
        $this->assign('title', '添加商品');
        return $this->fetch();
    }

    public function save(Request $request) {
        $params = $request->param();
        if ($params) {
            foreach ($params['pics'] as $k=>$v) {
                if ($v) {
                    $pics[$k] = $v;
                }
                
            }
            $pics = \json_encode($pics);
            $title  = self::getGoodsTitle($params['marker_id']);
            $data = [
                'title'     => $title,
                'sort_id'   => $params['sort_id'],
                'attr_id'   => $params['attr_id'],
                'marker_id' => $params['marker_id'],
                'pics'      => $pics,
                'parts'     => $params['parts'],
                'inventory' => $params['inventory'],
                'status'    => 1,
                'content'    => $params['content'],
            ];
           $id = Gd::insertGetId($data);

           if ($id) {
                $this->redirect('/admin/goods');
           } 
        }
    }

    public function checkGoods(Request $request) {
        $params = $request->param();
        $info = Gd::where(['sort_id' => $params['sort'], 'attr_id' => $params['attr'], 'marker_id' => $params['marker'], 'status' => ['<>', 0]])->find();
        if ($info) {
            return ['code' => 1, 'msg' => '商品已存在'];
        }

            return ['code' => 0, 'msg' => '成功'];
        
    }

    static public function getGoodsTitle($id) {
        
        $marker = \app\api\model\Marker::with('sort,attr')->find($id)->toArray();
        $title = $marker['sort']['name'] . ' ' . $marker['attr']['attr_name'] . ' ' . $marker['marker_name'];
        return $title;
    }

    public function edit($id)
    {
        $goods = \app\admin\model\Goods::where([
            'id' => $id
        ])->find();
        $sort = \app\admin\model\Sort::getList();
        $attr = \app\admin\model\Attribute::getAttrList($goods['sort_id']);
        $marker = \app\admin\model\Marker::getList($goods['attr_id']);
        $goods['pics'] = \json_decode($goods['pics'], true);
       
        $this->assign('goods', $goods);
        $this->assign('title','编辑商品');
        $this->assign('sortList', $sort);
        $this->assign('attr', $attr);
        $this->assign('marker', $marker);
        return $this->fetch();

    }
  
    public function update(Request $request, $id) {
        $params = $request->post();
        if ($params) {
            $goods = \app\admin\model\Goods::where([
                'id' => $id
            ])->find();

            foreach ($params['detail'] as $k=>$v) {
                if ($v) {
                    $pics[$k] = $v;
                }
            }
            $pics = \json_encode($pics);
           
            $title  = self::getGoodsTitle($params['marker_id']);

            $data = [
                'title'     => $title,
                'sort_id'   => $params['sort_id'],
                'attr_id'   => $params['attr_id'],
                'marker_id' => $params['marker_id'],
                'cover'     => $params['cover'],
                'pics'      => $pics,
                'parts'     => $params['parts'],
                'inventory' => $params['inventory'],
                'status'    => 1,
                'content'    => self::pregContent($params['content']),
                // 'content'    => $params['content'],
            ];

            $goods->save($data);
            $this->redirect('/admin/goods');
        }
    }

    static public function pregContent($content) {

        $content = preg_replace('/<\/?section>/i', '', $content);
        $content = preg_replace("/<p.*?>/", '<p style="font-size:12px">', $content);
        return $content;
    }

    public function delete(Request $request)
    {
        $params = $request->post();
        $model = \app\admin\model\Goods::where([
            'id' => $params['id']
        ])->find();
        $model->status = 0;
        $model->save();
    }

    public function ajaxChangeStatus()
    {
        $id = request()->post('goodsId', 0);
        $status = request()->post('status', 2);
        $model = \app\admin\model\Goods::where([
            'id' => $id
        ])->find();
        if (!$model) {
            return ['error'=> 1, 'msg' => '参数错误'];
        }
        $model->status = $status;
        $model->save();
        return ['error'=> 0, 'msg' => '成功'];
    }

    public function userGoods(Request $request, $id) {

        $status = $request->param('status', -1);
        $keywords = $request->param('keywords', '');

        
        if ($status == -1) {
            $status = ['neq', 0];

        }
        $hasWhere = [];
        if ($keywords) {
            $hasWhere['photo_user.username'] = ['like', '%' . $keywords . '%'];
        }

        $list = GoodsList::hasWhere('user', $hasWhere)->where(['status' => $status, 'goods_id' => $id])
                    ->order('displayorder asc, id desc')->paginate();
        $total = $list->total();
        $page = $list->render();

        foreach($list as $k=>$v) {
            $pics = json_decode($v['pics'], true);
            $list[$k]['pics'] = $pics[0];
        }

        $statusList = GoodsList::getStatus();
        $this->assign('total', $total);
        $this->assign('page', $page);
        $this->assign('list', $list);
        $this->assign('id', $id);
        $this->assign('status', $status);
        $this->assign('keywords', $keywords);
        $this->assign('statusList', $statusList);
        $this->assign('title', '商品列表');
        return $this->fetch();
        
    }

    public function userGoodsDetail(Request $request, $id) {
        $info = GoodsList::where(['id' => $id])->find();
        $pics = \json_decode($info['pics'], true);
        $detail = \json_decode($info['detail'], true);

        $info['pics'] = $pics;
        $info['detail'] = $detail;
        $status = GoodsList::getStatus($info['status']);
        $this->assign('title', '商品详情');
        $this->assign('model', $info);
        $this->assign('status', $status);
        $this->assign('id', $info['goods_id']);
        return $this->fetch();
    }

    public function ajaxDealGoodsStatus()
    {
        $request = Request::instance();
        $id = $request->param('id', '');
        $status = $request->param('status', 1);

        $gid = GoodsList::where(['id' => $id])->value('goods_id');
        $where = array(
            'id' => $id,
        );
        \app\admin\model\GoodsList::where($where)->update([
            'status' => $status,
            'update_time' => date('Y-m-d H:i:s')
        ]);
        
        if ($status == 3 || $status == 5) {
            Gd::where(['id' => $gid])->setDec('inventory');
        }
        if ($status == 2) {
            Gd::where(['id' => $gid])->setInc('inventory');
            
        }
        return ['error' => 0, 'msg' => '成功'];
    }
}
