<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Cookie;
use app\admin\model\Question as Qt;
use app\admin\model\Answer;
use library\Helper;

class Question extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {

        $id = $request->param('id', '');
        if ($id) {
            $this->export($id);
        }

        $list = Qt::where(['status' => 1])->order('id desc')->paginate();
        $this->assign('title', '问卷管理');
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $type = Qt::getType();
        $this->assign('type', $type);
        $this->assign('title', '添加问卷');
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
            $sTime = $params['start_time'];
            $eTime = $params['end_time'];
            unset($params['start_time'], $params['end_time']);
            $d = [
                'value'       => \json_encode($params),
                'start_time'  => $sTime,
                'end_time'    => $eTime,
            ];
           
            $model = new Qt;
            $model->create($d);
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
        $info = Qt::where(['id' => $id])->find()->toArray();
        $value = \json_decode($info['value'], true);
        foreach ($value as $k => $v) {
            if (!isset($v['type'])) {
                continue;
            }
            $type = $v['type'];
            if ($type == 1 || $type == 2) {
               
                $q    = $v['q'];
                $a    = $v['a'];
                $value[$k] = [
                    'type' => $type,
                    'q'    => $q,
                    'a'    => $a,
                ];
            } else {
                $q    = $v['q'];
                $value[$k] = [
                    'type' => $type,
                    'q'    => $q,
                ];
            }
            
        }
        $info['value'] = $value;
        $info['start_time'] = \explode(' ', $info['start_time'])[0];
        $info['end_time']   = \explode(' ', $info['end_time'])[0];
        $this->assign('info', $info);
        $this->assign('title', '编辑问题');
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
        $id = $id;
        $model = Qt::where(['id' => $id])->find();

        $params = $request->post();
        
        if ($params) {
            $sTime = $params['start_time'];
            $eTime = $params['end_time'];
            unset($params['start_time'], $params['end_time']);
            $d = [
                'value'       => \json_encode($params),
                'start_time'  => $sTime,
                'end_time'    => $eTime,
                'update_time' => date('Y-m-d H:i:s'),
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
        
        $info = Qt::where(['id' => $id])->find();
        if ($info) {
            $r = $info->save(['status' => 0]);
            if ($r) {
                return ['code' => 0, 'msg' => '删除成功'];
            }
        } 
        
        return ['code' => 1, 'msg' => '删除失败'];   
    }

    public function lists($id) {
        $id = $id;
        $this->assign('title', '提交列表');
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
        //
    }

    public function export($id) {
        $list = Answer::where(['qid' => $id])->select()->toArray();

        if (!$list) {
            echo '<script>alert("无导出数据!");history.go(-1);</script>';

            exit;
        }

        foreach($list as $k=>$v) {
            $answer[] = \json_decode($v['answer'], true);
        }

        foreach($answer as $k=>$v) {
            foreach($v as $ke=>$va) {
                $a = trim(implode($va['a'], ','), ',');
                $q = $va['q'];
                $new[$k][$ke]['q'] = $q;
                $new[$k][$ke]['a'] = $a;
            }
        }
        // exit();
// dump($new);

// foreach ($answer[0] as $k => $v) {
//     $q = $v['q'];
//     $a = trim(',', implode(',', $v['a']));
//     $data[] = ['a', $q, 50];
    
 
// }
// dump($data);
      
        $title = '专家预约';
       
        self::exportExcel($title, $new);
       
    }

    static public function exportExcel($expTitle, $data){

        include_once EXTEND_PATH.'PHPExcel/PHPExcel.php';//方法二
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName =  $xlsTitle;//or $xlsTitle 文件名称可根据自己情况设定
        //$cellNum = count($expCellName);
        //$dataNum = count($expTableData);

        $num = count($data[0]);
        //$objPHPExcel = new PHPExcel();//方法一
        $objPHPExcel = new \PHPExcel();//方法二
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
        // $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        
        for($i=0;$i<$num;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($cellName[$i])->setWidth(50);
            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'1', $data[0][$i+1]['q']);
            
        }
        $dataNum = count($data);
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$num;$j++){
                   
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), $data[$i][$j+1]['a']);
                    
                
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
}
