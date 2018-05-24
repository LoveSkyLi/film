<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\News as Nw;

class News extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        //
        $kw = $request->param('keyword', '');
        $where = [];
        if ($kw) {
            $where['title'] = ['like', '%'.$kw.'%'];
        }
        
        $list = Nw::where(['is_admin' => 2, 'status' => 1])->where($where)->order('pubdate desc')->paginate();

        $this->assign('title', '资讯管理');
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
        $this->assign('title', '新增资讯');
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
        // dump($params);exit();
        if ($params) {
            $title = $params['title'];
            $cover = $params['cover'];
            $author = $params['author'];
            $pubdate = date('Y-m-d H:i');

            $value = $params['content'];
            $arr = [];
            $i = 0;
            foreach($value as $k=>$v) {

                if (!$v['content']) {
                    continue;
                }
                $arr[$i]['type'] = $v['type'];
                $arr[$i]['content'] = $v['content'];
                $i++;
            }

            $content = \json_encode($arr);
            
            $data = [
                'title' => $title,
                'cover' => $cover,
                'author' => $author,
                'pubdate' => $pubdate,
                'content' => $content,
                'audit_status' => 5,
                'is_admin' => 2,
            ];

            Nw::create($data);

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
        $info = Nw::find($id)->toArray();
        $info['content'] = \json_decode($info['content'], true);
        // dump($info);
        $this->assign('title', '编辑资讯');
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
        $info = Nw::find($id);
        $params = $request->param();
        // dump($params);exit;
        if ($params) {
            $title = $params['title'];
            $cover = $params['cover'];
            $author = $params['author'];
            $pubdate =  $pubdate = date('Y-m-d H:i');

            $value = $params['content'];
            $arr = [];
            $i = 0;
            foreach($value as $k=>$v) {

                if (!$v['content']) {
                    continue;
                }
                $arr[$i]['type'] = $v['type'];
                $arr[$i]['content'] = $v['content'];
                $i++;
            }

            $content = \json_encode($arr);
            
            $data = [
                'title' => $title,
                'cover' => $cover,
                'author' => $author,
                'pubdate' => $pubdate,
                'content' => $content,
                'audit_status' => 5,
                'is_admin' => 2,
            ];

            //Nw::create($data);
            $info->save($data);
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
        
        $info = Nw::find($id);
        if ($info) {
            $r = $info->save(['status' => 0, 'audit_status' => 0]);
            if ($r) {
                return ['code' => 0, 'msg' => '删除成功'];
            }
        } 
        
        return ['code' => 1, 'msg' => '删除失败']; 
    }

    public function lists(Request $request) {
        $status = $request->param('status', -1);
        $where = [];
        if ($status != -1) {
            $where['audit_status'] = $status;
        }
        $list = Nw::where(['is_admin' => 1, 'status' => 1])->where($where)->order('id desc')->paginate();
        $statusList = Nw::getAuditStatusTextAttr();
        $this->assign('title', '用户资讯管理');
        $this->assign('list', $list);
        $this->assign('status', $status);
        $this->assign('statusList', $statusList);
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
        $info = Nw::where(['id' => $id])->find();
        $info['content'] = \json_decode($info['content'], true);
        $this->assign('title', '查看详情');
        $this->assign('info', $info);
        return $this->fetch();
    }

}
