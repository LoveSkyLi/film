<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Config;
use app\api\model\News as Nw;
use app\api\model\UserSession;
use app\api\model\NewsAuditLog as Nl;
use library\WxTemplateMsg;
use app\api\model\User;
use think\Log;

class News extends Base
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
    
    public function subNews(Request $request) {
        $uid = $this->uid;
        $title = $request->param('title', '');
        $cover = $request->param('cover', '');
        $author = $request->param('author', '');
        $content = $request->param('content', '');

        if (!$title || !$cover || !$author || !$content) {
            return ['code' => 1, 'msg' => '参数错误'];
        }

        $data = [
            'uid'          => $uid,
            'title'        => $title,
            'cover'        => $cover,
            'author'       => $author,
            'content'      => $content,
            'audit_status' => 1,
            'is_admin'     => 1,
        ];

        $rt = Nw::create($data);
        $id = $rt->id;

        if ($id) {
            $openid = User::where(['status' => 1, 'admingroup' => 1])->order('uid desc')->value('openid');
            if ($openid) {
                self::submitSendMsg($id, $openid);
            }

            return ['code' => 0, 'msg' => '提交成功', 'data' => ['id' => $id, 'uid' => $uid]];

        } else {
            return ['code' => 1, 'msg' => '提交失败'];
        }

    }

    public function getNewsLists (Request $request) {

        $kw = $request->param('keyword', '');

        $page = $request->param('page', 1, 'intval');
        if ($kw) {
            $where['title'] = ['like', '%'.$kw.'%'];
        }
        $where['status'] = 1;
        $where['audit_status'] = 5;
            
        $pageSize = Config::get('paginate.list_rows');

        $list = Nw::where($where)->field('id, title, cover, author, content, pubdate')->order('pubdate desc')->paginate($pageSize, false, ['page' => $page]);

        $list = $list->toArray();
        if (!$list['data']) {
            return ['code' => 1, 'msg' => '暂无数据'];
        }
        
        $d = $list['data'];
        foreach ($d as $k => $v) {
            $content = \json_decode($v['content'], true);
            $d[$k]['content'] = $content;
            $d[$k]['pubdate'] = date('Y-m-d', strtotime($v['pubdate']));
        }

        $pageTotal = ceil($list['total'] / $pageSize);

        $data = [
            'pageTotal' => $pageTotal,
            'page'      => $page,
            'size'      => $pageSize,
            'list'      => $d,
            'keyword'   => $kw,
        ];

        return ['code' => 0, 'msg' => '成功', 'data' => $data];
    }

    public function getNewsInfo (Request $request) {
        $id = $request->param('id', '');

        $info = Nw::field('id, title, cover, author, content, pubdate')->where(['id' => $id, 'status' => 1, 'audit_status' => 5])->find();

        if (!$info) {
            return ['code' => 1, 'msg' => '信息错误'];
        }

        $info = $info->toArray();
        $info['content'] = \json_decode($info['content'], true);
        $info['pubdate'] = date('Y-m-d', strtotime($info['pubdate']));
        return ['code' => 0, 'msg' => '成功', 'data' => $info];

    }

    

    public function updateNews(Request $request) {
        $uid = $this->uid;
        $id = $request->param('id', '');
        $title = $request->param('title', '');
        $cover = $request->param('cover', '');
        $author = $request->param('author', '');
        $content = $request->param('content', '');

        if (!$title || !$cover || !$author || !$content) {
            return ['code' => 1, 'msg' => '参数错误'];
        }

        $model = Nw::where(['id' => $id, 'audit_status' => 0, 'is_admin' => 1])->find();
        if (!$model) {
            return ['code' => 1, 'msg' => '信息错误'];
        }
        $data = [
            'title'        => $title,
            'cover'        => $cover,
            'author'       => $author,
            'content'      => $content,
            'audit_status' => 1,
        ];

        $rt = $model->save($data);

        if ($rt !== false) {
            $openid = User::where(['status' => 1, 'admingroup' => 1])->order('uid desc')->value('openid');
            if ($openid) {
                self::submitSendMsg($id, $openid);
            }
            return ['code' => 0, 'msg' => '修改成功', 'data' => ['id' => $id, 'uid' => $uid]];
        } else {
            return ['code' => 1, 'msg' => '修改失败'];
        }
    }


    public function getCheckNewsInfo (Request $request) {
        $id = $request->param('id', '');
        $type = $request->param('type', '');

        $info = Nw::field('id, title, cover, author, content, pubdate, create_time, audit_status')->where(['id' => $id, 'status' => 1])->find();

        if (!$info) {
            return ['code' => 1, 'msg' => '信息错误'];
        }

        $info = $info->toArray();
        $reason = '';
        if ($type == 2) {
            $reason = Nl::where(['news_id' => $id])->order('id desc')->value('reason');
        }

        $info['reason'] = $reason;
        $info['content'] = \json_decode($info['content'], true);
        if ($info['audit_status'] == 5) {
            $info['pubdate'] = date('Y-m-d', strtotime($info['pubdate']));
            
        } else {
            $info['pubdate'] = date('Y-m-d', strtotime($info['create_time']));
            
        }
        return ['code' => 0, 'msg' => '成功', 'data' => $info];

    }


    public function changeNewsStatus(Request $request) {
        $uid = $this->uid;
        $userData = $this->userData;

        if ($userData['admingroup'] <= 0) {
            return ['code' => 1, 'msg' => '无审核权限'];
        }

        $id = $request->param('id', '');
        $reason = $request->param('reason', '');
        $type = $request->param('type', '');//1通过，2不通过

        if (!$type) {
            return ['code' => 1, 'msg' => '参数错误'];
        }

        $info = Nw::where(['id' => $id])->find();
        if (!$info) {
            return ['code' => 1, 'msg' => '信息错误'];
        }
        Log::record(\json_encode($info->toArray()));
        
        if ($info->audit_status == 5) {
            return ['code' => 1, 'msg' => '资讯已发布'];
        }
        $aduitStatus = $userData['admingroup'];
        
        if ($aduitStatus != $info['audit_status']) {
            return ['code' => 1, 'msg' => '该资讯已审核'];
        }
        if ($type == 1) {
            $status =  $aduitStatus + 1;
            if ($status == 5) {
                $data = [
                    'audit_status' => $status,
                    'audit_time'   => date('Y-m-d H:i:s'),
                    'pubdate'      => date('Y-m-d H:i:s'),
                ];

            } else {
                $data = [
                    'audit_status' => $status,
                    'audit_time'   => date('Y-m-d H:i:s'),
                ];

            }

            $rt = Nw::where(['id' => $id])->update($data);
            if ($rt) {

                if ($status < 5) {
                    $openid = User::where(['status' => 1, 'admingroup' => $status])->order('uid desc')->value('openid');
                    if ($openid) {
                        self::submitSendMsg($id, $openid);
                    }
                    
                }
                
                return ['code' => 0, 'msg' => '操作成功', 'data' => ['id' => $id]];
            }

        } elseif ($type ==2) {

            if (!$reason) {
                return ['code' => 1, 'msg' => '请填写原因'];
            }

            $data = [
                'audit_status' => 0,
                'audit_time'   => date('Y-m-d H:i:s'),
            ];
            $rt = Nw::where(['id' => $id])->update($data);

            $d = [
                'news_id' => $id,
                'uid'     => $uid,
                'type'    => $aduitStatus,
                'reason'  => $reason,
            ];

            $r = Nl::insertGetId($d);

            if ($rt && $r) {
                $admingroup = $aduitStatus - 1;
                $openids = User::where(['status' => 1, 'admingroup' => ['between', [1, $admingroup]]])->order('uid desc')->column('openid');
                if ($openids) {
                    self::auditSendMsg($id, $openids);
                }

                return ['code' => 0, 'msg' => '操作成功', 'data' => ['id' => $id]];
            } else {
                return ['code' => 1, 'msg' => '操作失败'];
            }
        }
        
    }


//     public function sendMsg() {
//         //self::submitSendMsg('www.baidu.com', 'o7j4e1Qd0w-x_PyE48b1PIqEjft4');
//         $openids = User::where(['status' => 1, 'admingroup' => ['between', [1, 4]]])->order('uid desc')->column('openid');
// self::auditSendMsg(1, $openids);
//     }
    
    /**
     * 审核
     * GovnwBD2FlZ0wAJ9-i9mulb5pmXlCEFB811GPwnbexM
     */ 
    static public function submitSendMsg($id, $openid) {

        $url = 'https://debug.oa.feeyan.com/movie-mp/web/html/index/check.html?id='. $id .'&type=1';
        $templateId = 'GovnwBD2FlZ0wAJ9-i9mulb5pmXlCEFB811GPwnbexM';
        $params = [
            'touser' => $openid,//'o7j4e1Qd0w-x_PyE48b1PIqEjft4',
            'template_id' => $templateId,
            'url'   => $url,
            'data' => [
                'first' => [
                    'value' => '您有一条新的待审核申请',
                ],
                'keyword1' => [
                    'value' => '资讯上传申请',
                ],
                'keyword2' => [
                    'value' => '待您审核',
                ],
                
                'remark' =>[
                    'value' => '点击查看申请详情',
                ]
            ]
        ];
        WxTemplateMsg::send($params);
    }

    /**
     * 审核未通过
     * GovnwBD2FlZ0wAJ9-i9mulb5pmXlCEFB811GPwnbexM
     * 
     * url=https://debug.oa.feeyan.com/movie-mp/web/html/index/index_Details.html?id=59
     * 
     */

    static public function auditSendMsg($id, $openid) {

        $url = 'https://debug.oa.feeyan.com/movie-mp/web/html/index/newsRead.html?id='. $id .'&type=2';
        $templateId = 'GovnwBD2FlZ0wAJ9-i9mulb5pmXlCEFB811GPwnbexM';

        if (is_array($openid)) {
            foreach ($openid as $k=>$v) {
                $params = [
                    'touser' => $v,//'o7j4e1Qd0w-x_PyE48b1PIqEjft4',
                    'template_id' => $templateId,
                    'url'   => $url,
                    'data' => [
                        'first' => [
                            'value' => '上级未审核通过此条申请',
                        ],
                        'keyword1' => [
                            'value' => '资讯上传申请',
                        ],
                        'keyword2' => [
                            'value' => '审核未通过',
                        ],
                        
                        'remark' =>[
                            'value' => '点击查看申请详情',
                        ]
                    ]
                ];
                WxTemplateMsg::send($params);
            }
            

        } else {
            $params = [
                'touser' => $openid,//'o7j4e1Qd0w-x_PyE48b1PIqEjft4',
                'template_id' => $templateId,
                'url'   => $url,
                'data' => [
                    'first' => [
                        'value' => '您有一条新的待审核申请',
                    ],
                    'keyword1' => [
                        'value' => '资讯上传申请',
                    ],
                    'keyword2' => [
                        'value' => '待您审核',
                    ],
                    
                    'remark' =>[
                        'value' => '点击查看申请详情',
                    ]
                ]
            ];
            WxTemplateMsg::send($params);

        }

        
    }



}
