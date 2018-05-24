<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Meeting as Mt;
use app\admin\model\MeetingOrder as Mo;
class Meeting extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {

        $kw = $request->param('keyword', '');
        $where['status'] = 1;
        if ($kw) {
            $where['title'] = ['like', '%' . $kw . '%'];
        }

        $list = Mt::where($where)->order('id desc')->paginate();
        $this->assign('list', $list);
        $this->assign('keyword', $kw);
        $this->assign('title', '会议列表');
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $this->assign('title', '添加会议');
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
            $info_set = !empty($params['info_set']) ? \json_encode($params['info_set']) : '';
           
            $d = [
                'title' => $params['title'],
                'cover' => $params['cover'],
                'address' => $params['address'],
                'sTime'   => $params['sTime'],
                'eTime'   => $params['eTime'],
                'info_set' => $info_set,
                'intro'    => $params['intro'],
            ];

            $model = Mt::create($d);
            $this->redirect('index');
        }
    }

    

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $info = Mt::find($id)->toArray();
        $info_list = \json_decode($info['info_set']);
        $this->assign('info_list', $info_list);
        $this->assign('info', $info);
        $this->assign('title', '编辑会议');
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
            $model = Mt::find($id);
            $info_set = !empty($params['info_set']) ? \json_encode($params['info_set']) : '';
            
            $d = [
                'title' => $params['title'],
                'cover' => $params['cover'],
                'address' => $params['address'],
                'sTime'   => $params['sTime'],
                'eTime'   => $params['eTime'],
                'info_set' => $info_set,
                'intro'    => $params['intro'],
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
        
        $info = Mt::find($id);
        if ($info) {
            $r = $info->save(['status' => 0]);
            if ($r) {
                return ['code' => 0, 'msg' => '删除成功'];
            }
        } 
        
        return ['code' => 1, 'msg' => '删除失败']; 
    }

    public function lists(Request $request, $id) {
        $where['mid'] = $id;
        $status = $request->param('status', -1);

        if ($status > -1) {
            $where['status'] = $status;
        }
        $statusList = Mo::getStatusTextAttr();
        $list = Mo::where($where)->order('id desc')->paginate();

        $this->assign('title', '会议订单');
        $this->assign('status', $status);
        $this->assign('statusList', $statusList);
        $this->assign('list', $list);
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $info = Mo::where(['id' => $id])->find();
        $member = \json_decode($info['member'], true);
        $extend = \json_decode($info['extend'], true);
        if ($extend) {
            $extend = $extend;
        } else {
            $extend = '';
        }
        $this->assign('title', '订单详情');
        $this->assign('info', $info);
        $this->assign('member', $member);
        $this->assign('extend', $extend);
        return $this->fetch();
    }
}
