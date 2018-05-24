<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Admin as Am;
use app\admin\model\User;
class AdminUser extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $list = Am::where(['status' => 1])->select();
        $this->assign('title', '管理员管理');
        $this->assign('list', $list);
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
        $this->assign('title', '添加管理员');
        return $this->fetch();
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
        $params = $request->param();
        if ($params) {
            $admin = new Am;
            $admin->username = $params['username'];
            $admin->phone    = $params['phone'];
            $admin->password = User::generatePassword($params['password']);
            $admin->save();
            $this->redirect('/admin/admin_user/index');
            
        }
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
        $info = Am::where(['uid' => $id])->find();

        $this->assign('title', '编辑管理员');
        $this->assign('info', $info);
        return $this->fetch();
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $uid)
    {
        $params = $request->param();
        if ($params) {
            $admin = Am::where(['uid' => $uid])->find();
            $admin->username = $params['username'];
            $admin->phone    = $params['phone'];
            if ($params['password']) {
                $admin->password = User::generatePassword($params['password']);
            }
            $admin->save();
            $this->redirect('/admin/admin_user/index');
            
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $id = Request::instance()->param('id', '', 'intval');

        Am::where([
            'uid' => $id,
        ])->update(['status' => 0]);

        return ['code'=> 0, 'msg' => '成功'];
    }
}
