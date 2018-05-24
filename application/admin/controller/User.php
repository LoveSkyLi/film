<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;

class User extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $keyword = Request::instance()->param('keyword', '');

        $where = [];
        if ($keyword) {
            $where['nickname'] = ['like', "%" . $keyword . "%"];
        }
        $where['status'] = 1;
        $list = \app\admin\model\User::where($where)->order('uid', 'desc')->paginate(10);
        $page = $list->render();
        $isSuperAdmin = \app\admin\model\User::getIsSuperAdmin();

        $this->assign('title', '会员管理');
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('isSuperAdmin', $isSuperAdmin);
        $this->assign('keyword', $keyword);
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

    public function setAdmin(Request $request)
    {
        $uid = $request->param('uid', '');
        $status = $request->param('status', '');
        $where['uid'] = $uid;
        if ($status != 0) {
            \app\admin\model\User::where(['admingroup' => $status])->update(['admingroup' => 0]);
            
        }
        $user = \app\admin\model\User::where($where)->find();
        
        $user->admingroup = $status;
        $user->save();
        return ['code' => 0, 'msg' => '成功'];
    }

    public function undoAdmin(Request $request)
    {
        $uid = $request->param('uid', '');
        $status = $request->param('status', '');
        $where['uid'] = $uid;
        
        $user = \app\admin\model\User::where($where)->find();
        
        $user->usergroup = $status;
        $user->save();
        return ['code' => 0, 'msg' => '成功'];
    }

    public function bind()
    {
        $adminInfo = \app\admin\model\User::getAdminInfo();
        $qrcodeShow = 'http://www.feeyan.net/eshop/v/proxybind?appid='.$adminInfo['appid'];

        $this->assign('title', '接收消息设置');
        $this->assign('adminInfo', $adminInfo);
        $this->assign('qrcodeShow', $qrcodeShow);

        return $this->fetch();
    }
}
