<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Company as Cp;
class Company extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $kw = $request->param('keyword', '');
        $status = $request->param('status', -1, 'intval');
        $where = [];
        if ($kw) {
            $where['cname_en'] = ['like', '%'.$kw.'%'];
            $where['cname_cn'] = ['like', '%'.$kw.'%'];
        }

        if ($status > -1) {
            $where['status'] = $status;
        }
        $where['is_del'] = 1;
        $list = Cp::where($where)->order('id desc')->paginate();
        $statusList = Cp::getStatusTextAttr();
        $page = $list->render();
        $this->assign('title', '成员管理');
        $this->assign('list', $list);
        $this->assign('statusList', $statusList);
        $this->assign('s', $status);
        $this->assign('keyword', $kw);
        $this->assign('page', $page);
        return $this->fetch();
    }


    // public function getJw() {
    //     $j=file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak=mQ2hWNiaDGSCIIwGi81yQoa8Y2c1vihm&output=json&address=aaa');
         
    //         $j=json_decode($j,true); 
    // }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        $this->assign('title', '自提门店信息');
        
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
            $model = new \app\admin\model\Company();
            $model->name = $params['name'];
            $model->phone = $params['phone'];
            $model->address = $params['address'];
            $model->lnglat  = $params['lnglat'];
            $model->status  = $params['status'];
            $model->save();
            $this->redirect('/admin/company/index');
            
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
        $info = Cp::where(['id' => $id])->find();
        $this->assign('title', '查看会员信息');
        $this->assign('info', $info);
        return $this->fetch();
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $info = \app\admin\model\Company::where([
            'id' => $id
        ])->find();
        
        $this->assign('title', '修改成员信息');
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
        $params = $request->post();
        if ($params) {
            $model = \app\admin\model\Company::where([
                'id' => $id
            ])->find();
            $d = [
                'username' => $params['username'],
                'phone'    => $params['phone'],
                'email'    => $params['email'],
                'cname_cn' => $params['cname_cn'],
                'cname_en' => $params['cname_en'],
                'website'  => $params['website'],
                'application' => $params['application'],
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
    public function delete($id)
    {
        Cp::where([
            
            'id' => $id
        ])->update(['status' => 0]);

        return [
            'error' => 0,
            'msg' => '成功',
        ];
    }

    public function changeStatus(Request $request) {
        $params = $request->post();

        if ($params) {
            $model = Cp::where(['id' => $params['id']])->update(['status' => $params['status']]);
            if ($model !== false) {
                return ['code' => 0, 'msg' => '操作成功'];
            } else {
                return ['code' => 1, 'msg' => '操作失败'];
            }
        }
    }

    public function apply() {
        $cover = \app\admin\model\Setting::where(['type' => 1])->value('cover');
        $this->assign('title', '会员申请书模板');
        $this->assign('apply', $cover);
        return $this->fetch();
    }

    public function subApply(Request $request) {
        $value = $request->param('value');
        $rt = \app\admin\model\Setting::where(['type' => 1])->update(['cover' => $value]);
        if ($rt !== false) {
            return ['code' => 0, 'msg' => '设置成功', 'data' => []];
        } else {
            return ['code' => 1, 'msg' => '设置失败'];
        }
    }


    public function editMemo()
    {
        $reason = Request::instance()->param('reason', '');
        $id = Request::instance()->param('id', '');

        $model = \app\admin\model\Company::where([
            'id' => $id
        ])->find();
        if (!$model) {
            return ['error' => 1, 'msg' => '信息不存在'];
        }
        $model->status = 2;
        $model->save();

        $data = [
            'cid' => $model->id,
            'uid' => $model->uid,
            'reason' => $reason,
        ];
        
        \app\admin\model\CompanyAuditLog::create($data);
        
        return ['error' => 0, 'msg' => '成功'];
    }

}
