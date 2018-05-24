<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Experts as Ep;
class Experts extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $kw = $request->param('keyword', '');
        if ($kw) {
            $where['name'] = ['like', '%' . $kw . '%'];
        }
        $where['status'] = 1;
        $list = Ep::where($where)->order('id desc')->paginate();

        $this->assign('title', '专家列表');
        $this->assign('list', $list);
        $this->assign('keyword', $kw);
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $this->assign('title', '添加专家');
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
        $params = $request->param();

        if ($params) {
            $d = [
                'name'  => $params['name'],
                'breif' => $params['breif'],
                'avatar' => $params['avatar'],

            ];
            $model = Ep::create($d);
            $this->redirect('index');
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
        $info = Ep::find($id)->toArray();
        $this->assign('title', '编辑专家');
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
    public function update(Request $request, $id)
    {
        $params = $request->param();
        if ($params) {
            $model = Ep::find($id);
            $d = [
                'name'  => $params['name'],
                'breif' => $params['breif'],
                'avatar' => $params['avatar'],
                
            ];
            
            $model->save($d);
            $this->redirect('index');
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request)
    {
        $id = $request->post('id', '');
        
        $info = Ep::find($id);
        if ($info) {
            $r = $info->save(['status' => 0]);
            if ($r) {
                return ['code' => 0, 'msg' => '删除成功'];
            }
        } 
        
        return ['code' => 1, 'msg' => '删除失败']; 
    }
}
