<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Cookie;
use think\Lang;

class Setting extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $type = $this->request->param('type', '');

        switch ($type) {
            case 'rate':
                $info = \app\admin\model\Setting::where(['type' => 5])->value('value');
                break;
            case 'company':
                $info = \app\admin\model\Setting::where(['type' => 2])->find();
                break;
            // case 'slider':
            //     $list = \app\admin\model\Setting::where([
            //         'appid' => $appid,
            //         'type' => 1
            //     ])->select();
            //     break;
            // case 'navbar':
            //     $this->initNavbar();
            //     $list = \app\admin\model\Setting::where([
            //         'appid' => $appid,
            //         'type' => 2
            //     ])->select();
            //     break;
            // case 'adone':
            //     $list = \app\admin\model\Setting::where([
            //         'appid' => $appid,
            //         'type' => 3
            //     ])->select();
            //     break;
            // case 'adtwo':
            //     $this->initAdtwo();
            //     $list = \app\admin\model\Setting::where([
            //         'appid' => $appid,
            //         'type' => 4
            //     ])->select();
            //     break;
            // case  'adthree':
            //     $this->initAdthree();
            //     $list = \app\admin\model\Setting::where([
            //         'appid' => $appid,
            //         'type' => 5
            //     ])->select();
            //     break;
        }


        // foreach ($list as $key => $val) {
        //     $m = $val['module'];
        //     $resourceList = [
        //         ['id' => '', 'title' => '']
        //     ];
        //     if ($m == 'goods') {
        //         $resourceList = \app\admin\model\Goods::where([
        //             'appid' => $appid,
        //             'status' => 1
        //         ])->order('id', 'desc')->limit(50)->field(['id', 'title'])->select();
        //         foreach ($resourceList as $k => $v) {
        //             $v['id'] = '/pages/goods/detail/index?id='.$v['id'];
        //             $resourceList[$k] = $v;
        //         }
        //     } elseif ($m == 'article') {
        //         $resourceList = \app\admin\model\Article::where([
        //             'appid' => $appid,
        //             'status' => 1
        //         ])->order('id', 'desc')->limit(50)->field(['id', 'title'])->select();
        //         foreach ($resourceList as $k => $v) {
        //             $v['id'] = '/pages/article/detail/index?id='.$v['id'];
        //             $resourceList[$k] = $v;
        //         }
        //     } elseif ($m == 'type') {
        //         $sortlist = \app\admin\model\Sort::getList($appid);
        //         foreach ($sortlist as $k => $v) {
        //             $resourceList[$k] = [
        //                 'id' => '/pages/goods/type/index?type='.$v['id'],
        //                 'title' => $v['name']
        //             ];
        //         }
        //     } elseif ($m == 'page') {
        //         $resourceList = \app\admin\model\Setting::getPage();
        //     }

        //     $list[$key]['resourceList'] = $resourceList;
        // }

        $this->assign('title', '编辑基础信息');
        $this->assign('type', $type);
        $this->assign('info', $info);
		
		// $lang = 'en-us';
		// $lang = Lang::range($lang);//设定当前语言
// 　　　　Lang::load(APP_PATH.DS.'index'.DS.'lang'.DS.$lang.EXT,$lang);//加载当前语言包
// 　　　　Cookie::set('think_var',$lang);
		// $this->assign('set_lang',$lang);
		
        return $this->fetch($type);
    }

    public function company(Request $request) {
        $phone = $request->param('phone', '');
        $address = $request->param('address', '');
        $brief = $request->param('brief', '');
        $rt = \app\admin\model\Setting::where(['type' => 2])->update(['phone' => $phone, 'address' => $address, 'brief' => $brief]);
        if ($rt !== false) {
            return ['code' => 0, 'msg' => '设置成功'];
        } else {
            return ['code' => 1, 'msg' => '设置失败'];
        }
    }

    public function rate(Request $request) {
        $value = $request->param('value');
        $rt = \app\admin\model\Setting::where(['type' => 5])->update(['value' => $value]);
        if ($rt !== false) {
            return ['code' => 0, 'msg' => '设置成功', 'data' => []];
        } else {
            return ['code' => 1, 'msg' => '设置失败'];
        }
    }
    // public function initNavbar()
    // {
    //     $model = \app\admin\model\Setting::where([
    //         'appid' => $this->appid,
    //         'type' => 2
    //     ])->find();
    //     if (!$model) {
    //         for ($i=0; $i<4; $i++) {
    //             \app\admin\model\Setting::create([
    //                 'appid' => $this->appid,
    //                 'type' => 2
    //             ]);
    //         }
    //     }
    // }

    // public function initAdone()
    // {
    //     $model = \app\admin\model\Setting::where([
    //         'appid' => $this->appid,
    //         'type' => 3
    //     ])->find();
    //     if (!$model) {
    //         for ($i=0; $i<3; $i++) {
    //             \app\admin\model\Setting::create([
    //                 'appid' => $this->appid,
    //                 'type' => 3
    //             ]);
    //         }
    //     }
    // }

    // public function initAdtwo()
    // {
    //     $model = \app\admin\model\Setting::where([
    //         'appid' => $this->appid,
    //         'type' => 4
    //     ])->find();
    //     if (!$model) {
    //         \app\admin\model\Setting::create([
    //             'appid' => $this->appid,
    //             'type' => 4
    //         ]);
    //     }
    // }

    // public function initAdthree()
    // {
    //     $model = \app\admin\model\Setting::where([
    //         'appid' => $this->appid,
    //         'type' => 5
    //     ])->find();
    //     if (!$model) {
    //         for ($i=0; $i<3; $i++) {
    //             \app\admin\model\Setting::create([
    //                 'appid' => $this->appid,
    //                 'type' => 5
    //             ]);
    //         }
    //     }
    // }

    // public function sliderSave(Request $request)
    // {
    //     $type = 1;
    //     $params = $request->post();
    //     if ($params) {
    //         \app\admin\model\Setting::where([
    //             'appid' => $this->appid,
    //             'type' => $type,
    //         ])->delete();

    //         foreach ($params['value'] as $key => $val) {
    //             $id = $key;
    //             if (!$id) {
    //                 foreach ($val as $k => $v) {
    //                     \app\admin\model\Setting::create([
    //                         'appid' => $this->appid,
    //                         'type' => $type,
    //                         'title' => $v['title'],
    //                         'cover' => $v['cover'],
    //                         'module' => $v['module'],
    //                         'link' => $v['link'],
    //                         'displayorder' => $v['displayorder']
    //                     ]);
    //                 }
    //             } else {
    //                 \app\admin\model\Setting::create([
    //                     'appid' => $this->appid,
    //                     'type' => $type,
    //                     'title' => $val['title'],
    //                     'cover' => $val['cover'],
    //                     'module' => $val['module'],
    //                     'link' => $val['link'],
    //                     'displayorder' => $val['displayorder']
    //                 ]);
    //             }
    //         }
    //     }
    //     $this->redirect('/admin/setting/index?type=slider');
    // }

    // public function navbarSave(Request $request)
    // {
    //     $type = 2;
    //     $params = $request->post();
    //     if ($params) {
    //         foreach ($params['value'] as $key => $val) {
    //             $id = $key;
    //             \app\admin\model\Setting::where([
    //                 'appid' => $this->appid,
    //                 'id' => $id,
    //                 'type' => $type,
    //             ])->update($val);
    //         }
    //         $this->redirect('/admin/setting/index?type=navbar');
    //     }
    // }

    // public function adoneSave(Request $request)
    // {
    //     $type = 3;
    //     $params = $request->post();
    //     if ($params) {
    //         \app\admin\model\Setting::where([
    //             'appid' => $this->appid,
    //             'type' => $type,
    //         ])->delete();

    //         foreach ($params['value'] as $key => $val) {
    //             $id = $key;
    //             if (!$id) {
    //                 foreach ($val as $k => $v) {
    //                     \app\admin\model\Setting::create([
    //                         'appid' => $this->appid,
    //                         'type' => $type,
    //                         'title' => $v['title'],
    //                         'cover' => $v['cover'],
    //                         'module' => $v['module'],
    //                         'link' => $v['link'],
    //                         'displayorder' => $v['displayorder']
    //                     ]);
    //                 }
    //             } else {
    //                 \app\admin\model\Setting::create([
    //                     'appid' => $this->appid,
    //                     'type' => $type,
    //                     'title' => $val['title'],
    //                     'cover' => $val['cover'],
    //                     'module' => $val['module'],
    //                     'link' => $val['link'],
    //                     'displayorder' => $val['displayorder']
    //                 ]);
    //             }
    //         }
    //     }
    //     $this->redirect('/admin/setting/index?type=adone');
    // }

    // public function adtwoSave(Request $request)
    // {
    //     $type = 4;
    //     $params = $request->post();
    //     if ($params) {
    //         foreach ($params['value'] as $key => $val) {
    //             $id = $key;
    //             \app\admin\model\Setting::where([
    //                 'appid' => $this->appid,
    //                 'id' => $id,
    //                 'type' => $type,
    //             ])->update($val);
    //         }
    //         $this->redirect('/admin/setting/index?type=adtwo');
    //     }
    // }

    // public function adthreeSave(Request $request)
    // {
    //     $type = 5;
    //     $params = $request->post();
    //     if ($params) {
    //         foreach ($params['value'] as $key => $val) {
    //             $id = $key;
    //             \app\admin\model\Setting::where([
    //                 'appid' => $this->appid,
    //                 'id' => $id,
    //                 'type' => $type,
    //             ])->update($val);
    //         }
    //         $this->redirect('/admin/setting/index?type=adthree');
    //     }
    // }

    // public function ajaxGetLinkResource()
    // {
    //     $linkType = request()->post('linkType', 0);

    //     $appId = $this->appid;

    //     $where['appid'] = $appId;
    //     $where['status'] = 1;
    //     switch ($linkType) {
    //         case 'goods':
    //             $list = \app\admin\model\Goods::where($where)->order('id', 'desc')->limit(50)->field(['id', 'title'])->select();
    //             foreach ($list as $key => $val) {
    //                 $val['id'] = '/pages/goods/detail/index?id='.$val['id'];
    //                 $list[$key] = $val;
    //             }
    //             break;
    //         case 'article':
    //             $list = \app\admin\model\Article::where($where)->order('id', 'desc')->limit(50)->field(['id', 'title'])->select();
    //             foreach ($list as $key => $val) {
    //                 $val['id'] = '/pages/article/detail/index?id='.$val['id'];
    //                 $list[$key] = $val;
    //             }
    //             break;
    //         case 'type':
    //             $sortlist = \app\admin\model\Sort::getList($appId);
    //             $list = [];
    //             foreach ($sortlist as $key => $val) {
    //                 $list[] = [
    //                     'id' => '/pages/goods/type/index?type='.$val['id'],
    //                     'title' => $val['name']
    //                 ];
    //             }
    //             break;
    //         case 'page':
    //             $list = \app\admin\model\Setting::getPage();
    //             break;
    //     }

    //     return [
    //         'error' => 0,
    //         'msg' => '成功',
    //         'data' => $list
    //     ];
    // }
}
