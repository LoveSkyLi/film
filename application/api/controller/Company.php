<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Config;
use app\api\model\Company as Cp;
use app\api\model\User;
use app\api\model\UserSession;

class Company extends Base
{
    public function _initialize(){
        parent::_initialize();
        //74bcea8beb4fab3a771694408f58a8bf6dd3ccaea031149c058a23ea5e7f695c
        $userSession = isset($_REQUEST['userSession']) ? $_REQUEST['userSession'] : '';
        if (!$userSession) {
            exit(json_encode(['code' => 2, 'msg' => '参数错误']));
            
        } 
        $userData = UserSession::getInfo($userSession);
        if (!$userData) {
            exit(json_encode(['code' => 2, 'msg' => '用户不存在']));
        }

        $this->uid = $userData['uid'];
        $this->userData = $userData;
    }

    public function subApply(Request $request) {
        $uid = $this->uid;
        $contact = $request->param('contact', '');
        $phone   = $request->param('phone', '');
        $email   = $request->param('email' , '');
        $cname_cn = $request->param('cname_cn', '');
        $cname_en = $request->param('cname_en', '');
        $brief    = $request->param('brief', '');
        $website  = $request->param('website', '');
        $license  = $request->param('license', '');
        $apply    = $request->param('apply', '');

        if (!$contact || !$phone || !$email || !$cname_cn || !$cname_en || !$brief || !$website || !$license || !$apply ) {
            return ['code' => 1, 'msg' => '参数错误'];
        }

        $data = [
            'contact' => $contact,
            'phone'   => $phone,
            'email'   => $email,
            'cname_cn' => $cname_cn,
            'cname_en' => $cname_en,
            'brief'    => $brief,
            'website'  => $website,
            'license'  => $license,
            'apply'    => $apply,
            'uid'      => $uid,
        ];

        $model = Cp::create($data);

        if ($model) {
            User::where(['uid' => $uid])->update(['cid' => $model->id, 'cname' => $cname_cn, 'cphone' => $phone, 'contact' => $contact]);

            return ['code' => 0, 'msg' => '成功', 'data' => ['id' => $model->id]];
        } else {
            return ['code' => 1, 'msg' => '提交失败'];
        }

    }

    public function getCompanyInfo(Request $request) {
        $uid = $this->uid;
        $userData = $this->userData;

        $cid = $userData['cid'];
        if (!$cid) {
            return ['code' => 1, 'msg' => '没有公司信息'];
        }

        $info = Cp::where(['id' => $cid])->find();
        $info = $info->toArray();
        if (!$info) {
            return ['code' => 1, 'msg' => '没有公司信息'];
        }

        if ($info['status'] == 2) {
            $audit = \app\api\model\CompanyAuditLog::where(['cid' => $cid])->order('id desc')->value('reason');
        } else {
            $audit = '';
        }
        $info['audit'] = $audit;
        return ['code' => 0, 'msg' => '成功', 'data' => $info];
        
    }

    public function updateCompany(Request $request) {
        $uid = $this->uid;
        $userData = $this->userData;

        $cid = $userData['cid'];

        $id = $request->param('id', '');
        
        $info = Cp::where(['id' => $cid])->find();

        if ($cid != $id && !$info) {
            return ['code' => 1, 'msg' => '没有公司信息'];
        }

        $contact = $request->param('contact', '');
        $phone   = $request->param('phone', '');
        $email   = $request->param('email' , '');
        $cname_cn = $request->param('cname_cn', '');
        $cname_en = $request->param('cname_en', '');
        $brief    = $request->param('brief', '');
        $website  = $request->param('website', '');
        $license  = $request->param('license', '');
        $apply    = $request->param('apply', '');

        if (!$contact || !$phone || !$email || !$cname_cn || !$cname_en || !$brief || !$website || !$license || !$apply ) {
            return ['code' => 1, 'msg' => '参数错误'];
        }

        $data = [
            'contact' => $contact,
            'phone'   => $phone,
            'email'   => $email,
            'cname_cn' => $cname_cn,
            'cname_en' => $cname_en,
            'brief'    => $brief,
            'website'  => $website,
            'license'  => $license,
            'apply'    => $apply,
            'uid'      => $uid,
            'status'   => 0,
            'update_time' => date('Y-m-d H:i:s'),
        ];

        $rt = $info->save($data);
        if ($rt) {
            User::where(['uid' => $uid])->update(['cid' => $cid, 'cname' => $cname_cn, 'cphone' => $phone, 'contact' => $contact]);

            return ['code' => 0, 'msg' => '成功', 'data' => ['id' => $cid]];
        } else {
            return ['code' => 1, 'msg' => '提交失败'];
        }

    }
    
}
