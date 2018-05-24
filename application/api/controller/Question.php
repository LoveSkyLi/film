<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Config;
use app\api\model\Question as Qt;
use app\api\model\Answer;
use app\api\model\UserSession;

class Question extends Base
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
    }

    public function checkQuestion() {
        $where = [
            'start_time' => ['<', date('Y-m-d H:i:s')],
            'end_time'   => ['>', date('Y-m-d H:i:s')],
            'status'     => 1,
        ];

        $info = Qt::where($where)->order('id desc')->find();
        if (!$info) {
            return ['code' => 1, 'msg' => '无问卷'];
        }
        $id = $info->id;
        return ['code' => 0, 'msg' => '成功', 'data' => ['id' => $id]];
    }

    public function questionInfo(Request $request) {
        $uid = $this->uid;
        $id = $request->param('id', '');
        $where = [
            'start_time' => ['<', date('Y-m-d H:i:s')],
            'end_time'   => ['>', date('Y-m-d H:i:s')],
            'status'     => 1,
            'id'         => $id,
        ];
        $info = Qt::field('id, value')->where($where)->find();

        if (!$info) {
            return ['code' => 1, 'msg' => '问卷已过期或不存在'];
        }
        
        $info = $info->toArray();
        $info['value'] = \json_decode($info['value'], true);

        foreach ($info['value'] as $k => $v) {
            if ($v['type'] == 1 || $v['type'] == 2) {
                $v['a'] = \explode("\r\n", $v['a']);
            }
            $info['question'][] = $v;
        }
        unset($info['value']);
        return ['code' => 0, 'msg' => '成功', 'data' => $info];
    }

    public function subAnswer(Request $request) {

        $uid = $this->uid;

        $qid = $request->param('id', '');

        $answer = $request->param('answer', '');

        if (!$qid || !$answer) {
            return ['code' => 1, 'msg' => '参数错误'];
        }

        $data = [
            'uid' => $uid,
            'qid' => $qid,
            'answer' => $answer,
            'addtime' => date('Y-m-d H:i:s'),
        ];
        $model = Answer::create($data);

        if ($model->id) {
            return ['code' => 0, 'msg' => '提交成功', 'data' => ['id' => $model->id]];
        } else {
            return ['code' => 1, 'msg' => '提交失败'];
        }


    }


}
