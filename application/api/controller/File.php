<?php

namespace app\api\controller;

use app\api\model\UserSession;
use OSS\Core\OssException;
use OSS\OssClient;
use think\Config;
use think\Controller;
use think\Request;
use app\api\model\Article;

class File extends Base
{
    public function upload()
    {
        if (request()->isPost()) {
            $request = Request::instance();
            $userSession = $request->param('userSession', '');

            // $userData = UserSession::getInfo($userSession);
            // if (!$userData) {
            //     return ['code' => 1, 'msg' => '用户不存在'];
            // }

            $endpoint = config('oss.endpoint_internal');
            $bucket = config('oss.bucket');
            $accessId = config('oss.access_id');
            $accessSecret = config('oss.access_secret');
            $ossHost = config('oss.cdn_host');

            $file = request()->file('file');
            $file->validate(['size'=>20*1024*1024, 'ext'=>'jpg,jpeg,png,gif,mp4']);
            if (!$file->check()) {
                return ['code' => 1, 'msg' => $file->getError()];
            }

            $filePath = $file->getRealPath();
            $content = file_get_contents($filePath);

            $size = $file->getInfo('size');
            $ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);

            $date = Date('Y').'/'.Date('m');
            $folder = "film/$date/";
            $fileName = $this->createRandomStr(32).'.'.$ext;
            $object = $folder.$fileName;

            try {
                $ossClient = new OssClient($accessId, $accessSecret, $endpoint);
                $result = $ossClient->putObject($bucket, $object, $content);
            } catch (OssException $e) {
                return ['code' => 1, 'msg' => '上传文件失败'];
            }

            return [
                'code' => 0,
                'msg' => '成功',
                'data' => [
                    'url' => $ossHost.'/'.$object,
                    'size' => $size
                ]
            ];
        }
    }

    private function createRandomStr($length){
        $str = array_merge(range(0,9), range('a','z'), range('A','Z'));
        shuffle($str);
        $str = implode('',array_slice($str,0,$length));
        return $str;
    }
}
