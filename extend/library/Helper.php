<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 11:26
 */

namespace library;

use Curl\Curl;
use think\Log;

class Helper
{
    public static function curlPost($url, $data = [])
    {
        $curl  = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->post($url, $data);
        return $curl->response;
    }

    static public function http($url, $method='POST', $postfields = null, $headers = array(), $debug = false)
    {
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);

        switch ($method) {
            case 'POST':
            curl_setopt($ci, CURLOPT_POST, true);
            if (!empty($postfields)) {
                curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
            }
            break;
        }
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);

        $response = curl_exec($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);

        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);

            echo '=====info=====' . "\r\n";
            print_r(curl_getinfo($ci));

            echo '=====$response=====' . "\r\n";
            print_r($response);
        }
        curl_close($ci);
        return array($http_code, $response);
    }

    
    public static function authcode($string, $operation, $key = '')
    {
        $ckey_length = 4;

        $key = md5($key ? $key : 'asdf!@#$song1980');
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', isset($expiry) ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }

    }

    public static function createRandomStr($length) {
        $str = array_merge(range(0,9), range('a','z'), range('A','Z'));
        shuffle($str);
        $str = implode('',array_slice($str,0,$length));
        return $str;
    }

    public static function replaceRichText($content)
    {
        $content = htmlspecialchars_decode($content);
        $content = preg_replace('/<img\s*[^>]*?src="(.+?)"\s*[^>]*>/i', '<img src="$1" style="width: 100%;">', $content);
        $content = preg_replace('/<img\s*[^>]*?src="([^\?]+)\?+([^\?]*?)"\s*[^>]*>/i', '<img src="$1" style="width: 100%;">', $content);
        $content = preg_replace('/<\/?section>/i', '', $content);

        return $content;
    }

    public static function generateSkey($openid, $wxappid)
    {
        $str = $openid.$wxappid.'film';
        $skey = hash("sha256", $str);
        return $skey;
    }

    public static function generateSignature($arr)
    {
        $privateKey = 'film';
        ksort($arr);
        $str = '';
        foreach ($arr as $key => $val) {
            $str .= $key.'='.$val.'&';
        }
        $str = trim($str, '&');
        $signTemp = 'key='.$privateKey.'&'.$str;
//        Log::record($signTemp);
        $sign = md5($signTemp);
        $sign = strtoupper($sign);
        return $sign;
    }
    
    public static function getSensitiveWord($str)
    {
        $url = 'http://www.hoapi.com/index.php/Home/Api/check';
        $token = '77dbcc39130188fa5905d226d72bd4e6';

        $data = [
            'str' => $str,
            'token' => $token
        ];
        $result = self::curlPost($url, $data);
        $sensitiveInfo = json_decode($result, TRUE);
        return $sensitiveInfo;
    }

    public static function getOrderSn($param = '') {
        if ($param) {
            return date('Ymdhis').substr(microtime(),2,5).rand(100000, 999999) . $param;
        }
        return date('Ymdhis').substr(microtime(),2,5).rand(100000, 999999);
    }


    


    static public function exportExcel($expTitle, $expCellName, $expTableData){

        include_once EXTEND_PATH.'PHPExcel/PHPExcel.php';//方法二
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName =  $xlsTitle;//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        //$objPHPExcel = new PHPExcel();//方法一
        $objPHPExcel = new \PHPExcel();//方法二
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
        // $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($cellName[$i])->setWidth($expCellName[$i][2]);
            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'1', $expCellName[$i][1]);
            
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                if (is_array($expCellName[$j][0])) {
                    $arr = $expCellName[$j][0];
                    $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), $expTableData[$i][$arr[0]][$arr[1]]);
                    
                } else {
                    
                    $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), $expTableData[$i][$expCellName[$j][0]]);
                    
                }
            }
        }
        ob_end_clean();//这一步非常关键，用来清除缓冲区防止导出的excel乱码
        header('pragma:public');
        header('Content-Type: application/msexcel');

        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//"xls"参考下一条备注
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');//"Excel2007"生成2007版本的xlsx，"Excel5"生成2003版本的xls
        $objWriter->save('php://output');
        exit;
    }


    //获得视频文件的缩略图
    static function getVideoCover($file,$time,$name) {
        if(empty($time))$time = '1';//默认截取第一秒第一帧
        $strlen = strlen($file);
        // $videoCover = substr($file,0,$strlen-4);
        // $videoCoverName = $videoCover.'.jpg';//缩略图命名
        //exec("ffmpeg -i ".$file." -y -f mjpeg -ss ".$time." -t 0.001 -s 320x240 ".$name."",$out,$status);
        $str = "ffmpeg -i ".$file." -y -f mjpeg -ss 3 -t ".$time." -s 320x240 ".$name;
        //echo $str."</br>";
        $result = system($str);
    }

    //获得视频文件的总长度时间和创建时间
    static function getTime($file){
        $vtime = exec("ffmpeg -i ".$file." 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");//总长度
        $ctime = date("Y-m-d H:i:s",filectime($file));//创建时间
        //$duration = explode(":",$time);
        // $duration_in_seconds = $duration[0]*3600 + $duration[1]*60+ round($duration[2]);//转化为秒
        return array('vtime'=>$vtime,
        'ctime'=>$ctime
        );
    }

}