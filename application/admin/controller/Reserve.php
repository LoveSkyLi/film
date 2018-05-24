<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Reserve as Re;
use app\admin\model\ReserveTimes as Rt;
use app\admin\model\Experts;
use app\admin\model\User;
use app\admin\model\Company;
use library\Helper;

class Reserve extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $kw = $request->param('keyword', '');
        $hasWhere = [];
        if ($kw) {
            $hasWhere['film_experts.name'] = ['like', '%' . $kw . '%'];
        }
        $list = Re::hasWhere('experts', $hasWhere)->where(['film_reserve.status' => 1])->order('id desc')->paginate();
        $this->assign('title', '预约列表');
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
        $experts = Experts::getList();
        $this->assign('title', '添加预约');
        $this->assign('experts', $experts);
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
        // dump($params);exit();
        if ($params) {
            $ename = Experts::getName($params['eid']);

            $d = [
                'eid' => $params['eid'],
                'ename' => $ename,
                'title' => $params['title'],
                'address' => $params['address'],
                'intro'   => $params['intro'],
            ];
            $model = Re::create($d);
            $rid = $model->id;
            if ($rid) {
                foreach($params['rDate'] as $k=>$v) {
                    $name = 'times_' . ($k + 1);
                    $newArr = array_chunk($params[$name], 2);
    
                    foreach($newArr as $key=>$val) {
                        if (!$val[0] || !$val[1]) {
                            continue;
                        }
                        $data[] = [
                            'eid' => $params['eid'],
                            'ename' => $ename,
                            'rDate' => $v,
                            'sTime' => $val[0],
                            'eTime' => $val[1],
                            'rid'   => $rid,
                        ] ;
                    }
                }
                Rt::insertAll($data);
                $this->redirect('index');
            }
           
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
        $info = Re::find($id);
        $experts = Experts::getList();
        $dateList = Rt::getDateList($info['id']);
        // dump($dateList);exit();
        $this->assign('title', '编辑预约');
        $this->assign('info', $info);
        $this->assign('experts', $experts);
        $this->assign('dateList', $dateList);
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
        //
        $params = $request->param();
        // dump($params);exit();
        if($params) {

            $model = Re::find($id);

            $ename = Experts::getName($params['eid']);

            $d = [
                'eid' => $params['eid'],
                'ename' => $ename,
                'title' => $params['title'],
                'address' => $params['address'],
                'intro'   => $params['intro'],
            ];
            
            $model = $model->save($d);
            
            $x = 1;
            foreach($params['rDate'] as $k=>$v) {
                $name = 'times_' . ($k + 1);
                $newArr = array_chunk($params[$name], 2);

                foreach($newArr as $key=>$val) {
                    if (!$val[0] || !$val[1]) {
                        continue;
                    }
                    if(strlen($val[0]) > 6) {
                        $val[0] = date('H:i', strtotime($val[0]));
                    }
                    if(strlen($val[1]) > 6) {
                        $val[1] = date('H:i', strtotime($val[1]));
                    }
                    $y = $key + 1;
                    $iid = 'id_'.$x.'_'.$y;
                    if (isset($params[$iid])) {
                        $data[] = [
                            'id'  => $params[$iid],
                            'eid' => $params['eid'],
                            'ename' => $ename,
                            'rDate' => $v,
                            'sTime' => $val[0],
                            'eTime' => $val[1],
                            'rid'   => $id,
                        ] ;
                    } else {
                        $data[] = [
                            // 'id'  => $params[$iid],
                            'eid' => $params['eid'],
                            'ename' => $ename,
                            'rDate' => $v,
                            'sTime' => $val[0],
                            'eTime' => $val[1],
                            'rid'   => $id,
                        ] ;
                    }
                    
                }
                $x++;
            }
            $times = new Rt();
            $times->saveAll($data);
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
        $params = $request->post(); 
        if ($params) {
            $id = $params['id'];
            $reserve = Re::where(['id' => $id])->update(['status' => 0]);
            $times = Rt::where(['rid' => $id])->update(['status' => 0]);

            if ($reserve !== false && $times !== false) {
                return ['code' => 0, 'msg' => '删除成功'];
            } else {
                return ['code' => 1, 'msg' => '删除失败'];
            }
        }
    }

    public function delDate(Request $request) {
        $params = $request->post();

        if ($params) {
            $rid   = $params['rid'];
            $rDate = $params['rDate'];

            $model = Rt::where(['rid' => $rid, 'rDate' => $rDate])->update(['status' => 0]);
            if ($model !== false) {
                return ['code' => 0, 'msg' => '删除成功'];
            } else {
                return ['code' => 1, 'msg' => '删除失败'];
            }
            
        }
    }

    public function delTimes(Request $request) {
        $params = $request->post();
        if ($params) {
            $id = $params['id'];
            $rid = $params['rid'];
            $model = Rt::where(['rid' => $rid, 'id' => $id])->update(['status' => 0]);
            if ($model !== false) {
                return ['code' => 0, 'msg' => '删除成功'];
            } else {
                return ['code' => 1, 'msg' => '删除失败'];
            }
        }
    }

    public function lists(Request $request) {

        $kw = $request->param('keyword', '');
        $sDate = $request->param('sDate', '');
        $eDate = $request->param('eDate', '');
        $export = $request->param('export', '');
        if ($export == 1) {
            $this->export($request->param());
        }
        $where = [];
        if ($sDate && $eDate) {
            $where['rDate'] = ['between', [$sDate, $eDate]]; 
        }
        $hasWhere = [];
        if ($kw) {
            $hasWhere = [
                'name' => ['like', '%'.$kw.'%'],
            ];
        }
        $list = Rt::hasWhere('experts', $hasWhere)->with('reserve,experts')->where(['film_reserve_times.status' => 2])->where($where)->order('rDate desc, sTime asc')->paginate();
        $this->assign('title', '已预约列表');
        $this->assign('list', $list);
        $this->assign('keyword', $kw);
        $this->assign('sDate', $sDate);
        $this->assign('eDate', $eDate);
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
        $info = Rt::with('reserve,experts')->find($id);
        $user = User::where(['uid' => $info->uid])->field('uid, nickname, avatar, cid')->find();
        $company = Company::where(['id' => $user->cid])->field('id, contact, cname_cn, cname_en, brief, website')->find();
        
        $this->assign('title', '查看预约详情');
        $this->assign('model', $info);
        $this->assign('user', $user);
        $this->assign('company', $company);
        return $this->fetch();
    }

    public function export($data) {
        $kw = $data['keyword'];
        $sDate = $data['sDate'];
        $eDate = $data['eDate'];
        $where = [];
        if ($sDate && $eDate) {
            $where['rDate'] = ['between', [$sDate, $eDate]]; 
        }

        $hasWhere = [];
        if ($kw) {
            $hasWhere = [
                'name' => ['like', '%'.$kw.'%'],
            ];
        }
        $list = Rt::hasWhere('experts', $hasWhere)->with('reserve,experts')->where(['film_reserve_times.status' => 2])->where($where)->order('rDate desc, sTime asc')->select();

        $list = $list->toArray();

        if (!$list) {
            echo '<script>alert("无导出数据!");history.go(-1);</script>';

            exit;
        }

        foreach($list as $k=>$v) {
            $list[$k]['time'] = date('H:i', strtotime($v['sTime'])) .'-'. date('H:i', strtotime($v['eTime']));
        }
        

        $data = [
            [['experts', 'name'], '专家名称', 10],
            [['reserve', 'title'], '洽谈标题', 30],
            [['reserve', 'address'], '洽谈地址', 50],
            ['rDate', '日期', 15],
            ['time', '时间', 15],
            ['contact', '联系人', 15],
            ['phone', '联系方式', 15],
            ['cname', '公司名称', 20],
        ];

        // $xlsCell = [['uid','id'],['rDate','日期']];
        $title = '专家预约';
        if ($kw) {
            $title = $kw;
        }
        Helper::exportExcel($title, $data, $list);
    // self::createtable($list,'11',['id','rDate'],['id','rDate'] );
       
    }

    // static protected function createtable($list,$filename,$header=array(),$index = array()){    
    //     header("Content-type:application/vnd.ms-excel");    
    //     header("Content-Disposition:filename=".$filename.".xls");    
    //     $teble_header = implode("\t",$header);  
    //     $strexport = $teble_header."\r";  
    //     foreach ($list as $row){    
    //         foreach($index as $val){  
    //             $strexport.=$row[$val]."\t";     
    //         }  
    //         $strexport.="\r";   
      
    //     }    
    //     $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);    
    //     exit($strexport);       
    // } 


}
