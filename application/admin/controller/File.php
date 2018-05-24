<?php

namespace app\admin\controller;

use library\Helper;
use OSS\Core\OssException;
use OSS\OssClient;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use app\admin\model\Lcon;
use think\Controller;
use think\Db;

class File extends Base
{
    public function upload()
    {
        $action = request()->param('action', '');
        switch ($action) {
            case 'config':
                return $this->getConfig();
                break;
            case 'uploadimage':
                if (request()->isPost()) {
                    $endpoint = config('oss.endpoint_internet');
                    $bucket = config('oss.bucket');
                    $accessId = config('oss.access_id');
                    $accessSecret = config('oss.access_secret');
                    $ossHost = config('oss.cdn_host');

                    $file = request()->file('fileInfo');
                    $file->validate(['size'=>20*1024*1024, 'ext'=>'jpg,jpeg,png,gif,mp4']);
                    if (!$file->check()) {
                        $data = [
                            'error' => 1,
                            'state' => $file->getError(),
                        ];
                        return json($data);
                    }
                    $filePath = $file->getRealPath();
                    $content = file_get_contents($filePath);
                    $ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);

                    $dar = Date('Y').'/'.Date('m');
                    $folder = "film/$dar"."/";
                    $fileName = Helper::createRandomStr(32).'.'.$ext;
                    $object = $folder.$fileName;

                    try {
                        $ossClient = new OssClient($accessId, $accessSecret, $endpoint);
                        $ossClient->putObject($bucket, $object, $content);
                    } catch (OssException $e) {
                        $data = [
                            'error' => 1,
                            'state' => '上传失败'
                        ];
                        return json($data);
                    }

                    $data = [
                        'error' => 0,
                        'state' => 'SUCCESS',
                        'url' => $ossHost.'/'.$object
                    ];

                    return json($data);
                }
                break;
        }
    }

    private function getConfig()
    {
        $config = [
            "imageActionName" => "uploadimage", /* 执行上传图片的action名称 */
            "imageFieldName" => "fileInfo", /* 提交的图片表单名称 */
            "imageMaxSize" => 204800000, /* 上传大小限制，单位B */
            "imageAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp", ".mp4"], /* 上传图片格式显示 */
            "imageUrlPrefix" => "", /* 图片访问路径前缀 */
        ];

        return json($config);
    }
}
