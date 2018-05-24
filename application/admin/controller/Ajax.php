<?php

namespace app\admin\controller;

use library\WxTemplateMsg;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use app\admin\model\Item;
use app\admin\model\Article;
use app\admin\model\ArticleThumbnail;
use app\admin\model\Scenic;
use app\admin\model\ScenicTicket;
use app\admin\model\User;
use think\Controller;

class Ajax extends Base
{
    public function index()
    {
        echo rand(0, 9999);
        return json(['error'=>0, 'msg' => 'success']);
    }

    public function delPic()
    {
        $id = request()->post('id', 0);

        ArticleThumbnail::where([
            'appid' => $this->appid,
            'id' => $id
        ])->delete();

        return [
            'error' => 0,
            'msg' => '成功',
        ];
    }

    public function setCover()
    {
        $id = request()->post('id', 0);
        $articleId = request()->post('articleId', 0);

        ArticleThumbnail::where([
            'appid' => $this->appid,
            'article_id' => $articleId
        ])->update(['is_cover' => 0]);

        $model = ArticleThumbnail::where([
            'appid' => $this->appid,
            'id' => $id
        ])->find();
        $model->is_cover = 1;
        $model->save();

        return [
            'error' => 0,
            'msg' => '成功',
        ];
    }

    public function savePic()
    {
        $articleId = request()->post('articleId', 0);
        $url = request()->post('url', 0);

        $model = ArticleThumbnail::create([
            'appid' => $this->appid,
            'article_id' => $articleId,
            'thumbnail_url' => $url
        ]);

        $data = [
            'id' => $model->id,
            'url' => $model->thumbnail_url
        ];

        return [
            'error' => 0,
            'msg' => '成功',
            'data' => $data
        ];
    }

    public function articleDisplay()
    {
        $articleId = request()->post('articleId', 0);

        $model = Article::where([
            'appid' => $this->appid,
            'id' => $articleId
        ])->find();
        if (!$model) {
            return ['error'=> 1, 'msg' => '参数错误'];
        }
        $model->status = 1 - $model->status;
        $model->save();
        $msg = $model->status ? '隐藏' : '显示';
        return ['error'=> 0, 'msg' => $msg];
    }

    public function itemDisplay()
    {
        $itemId = request()->post('itemId', 0);

        $model = Item::where([
            'appid' => $this->appid,
            'id' => $itemId
        ])->find();
        if (!$model) {
            return ['error'=> 1, 'msg' => '参数错误'];
        }
        $model->status = 1 - $model->status;
        $model->save();
        $msg = $model->status ? '隐藏' : '显示';
        return ['error'=> 0, 'msg' => $msg];
    }

    public function itemRecommend()
    {
        $itemId = request()->post('itemId', 0);

        $model = Item::where([
            'appid' => $this->appid,
            'id' => $itemId
        ])->find();
        if (!$model) {
            return ['error'=> 1, 'msg' => '参数错误'];
        }
        $model->is_recommend = 1 - $model->is_recommend;
        $model->save();
        $msg = $model->is_recommend ? '取消推送' : '推送首页';
        return ['error'=> 0, 'msg' => $msg];
    }

    public function scenicDisplay()
    {
        $itemId = request()->post('itemId', 0);

        $model = Scenic::where([
            'appid' => $this->appid,
            'id' => $itemId
        ])->find();
        if (!$model) {
            return ['error'=> 1, 'msg' => '参数错误'];
        }
        $model->status = 1 - $model->status;
        $model->save();
        $msg = $model->status ? '隐藏' : '显示';
        return ['error'=> 0, 'msg' => $msg];
    }

    public function delScenicTicket()
    {
        $id = request()->post('id', 0);

        ScenicTicket::where([
            'appid' => $this->appid,
            'id' => $id
        ])->update(['status' => 0]);

        return [
            'error' => 0,
            'msg' => '成功',
        ];
    }

    public function bindPhone()
    {
        $phone = request()->post('phone', '');
        if(!WxTemplateMsg::checkPhone($phone)) {
            return ['error'=> 1, 'msg' => '请填写您在平台注册的手机号'];
        }
        $adminInfo = User::getAdminInfo();
        User::where([
            'appid' => $adminInfo['appid'],
            'uid' => $adminInfo['uid']
        ])->update(['phone' => $phone]);

        return [
            'error' => 0,
            'msg' => '成功',
        ];
    }

    public function scenicSort()
    {
        $ids = request()->post('ids/a', []);

        foreach ($ids as $key => $id) {
            $model = Scenic::where([
                'appid' => $this->appid,
                'id' => $id
            ])->update(['sort' => 1, 'sort_rank' => $key+1]);
        }

        return [
            'error' => 0,
            'msg' => '成功',
        ];
    }

    public function upload()
    {
        sleep(2);
        return [
            'error' => 0,
            'url' => 'http://osi68v71c.bkt.clouddn.com/house/2017/11/mOVv2xRs1LJrSjE3izpXIotudfG6h9Wl.jpg'
        ];
        if (request()->isPost()) {
            $url = config('qiniu.url');
            $bucket = config('qiniu.bucket');
            $accessKey = config('qiniu.access_key');
            $secretKey = config('qiniu.secret_key');

            $file = request()->file('fileInfo');

            $filePath = $file->getRealPath();
            $ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);

            $dar = Date('Y').'/'.Date('m');
            $folder = "shop/$dar/";
            $fileName = $this->createRandomStr(32).'.'.$ext;
            $src = $folder.$fileName;
            // 上传到七牛后保存的文件名
            $key = $src;

            $auth = new Auth($accessKey, $secretKey);
            $token = $auth->uploadToken($bucket);
            // 初始化 UploadManager 对象并进行文件的上传
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

            if ($err !== null) {
                $data = [
                    'error' => 1,
                    'msg' => 'fail',
                    'url' => ''
                ];
            } else {
                $data = [
                    'error' => 0,
                    'msg' => 'success',
                    'url' => $url.$ret['key']
                ];
            }
            return json($data);
        }
    }

    private function createRandomStr($length) {
        $str = array_merge(range(0,9), range('a','z'), range('A','Z'));
        shuffle($str);
        $str = implode('',array_slice($str,0,$length));
        return $str;
    }
}
