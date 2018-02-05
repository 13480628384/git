<?php
/**
 * Menu(机构管理)
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class PackageController extends AdminbaseController {
    protected $excel;
    public function _initialize() {
        parent::_initialize();
        vendor('ExcelTwo');
        $this->excel = new \ExcelTwo();
        vendor('UploadFile');
    }
    public function add() {
        $data = array(
            array(
                'scan_code'=>"ZHGZPY00000",
                'owner_id'=>'物联',
                'office_id'=>'玫瑰物联部门',
                'company_id'=>'深圳玫瑰物联有限公司',
                'area_id'=>'宝安区',
                'device_code'=>'A352425021229967',
                'device_command'=>'4051615',
                'device_type'=>'娃娃机',
                'pay_price'=>'1',
            ),
            array(
                'scan_code'=>"ZHGZPY00001",
                'owner_id'=>'物联1',
                'office_id'=>'玫瑰物联部门1',
                'company_id'=>'深圳玫瑰物联有限公司1',
                'area_id'=>'南山区',
                'device_code'=>'A352425021229968',
                'device_command'=>'4051616',
                'device_type'=>'洗衣机',
                'pay_price'=>'1',
            )
        );

        $this->excel->arr2ExcelDownload($data,
            array('扫描码','归属用户','归属部门','归属公司','归属地区',
                '硬件设备码','硬件指令码','设备类型','价格'),'批量添加设备模板');
    }
    public function download(){
        $data = array(
            array(
                'device_command'=>"4051823",
                'group_num'=>'A',
                'group_name'=>'广州',
                'office_name'=>'深圳部门'
            ),
            array(
                'device_command'=>"4051823",
                'group_num'=>'B',
                'group_name'=>'中山',
                'office_name'=>'广州部门'
            ),
        );
        $this->excel->arr2ExcelDownload($data,array('硬编码','群组编号','群组名称','部门'),'设备安装模板');
    }
    //批量添加与安装设备
    public function add_all(){
        $this->display();
    }
    //批量添加设备
    public function batch(){
        $upload = new \UploadFile();
        $upload->maxSize = 8145728;
        $upload->allowExts = array('xlsx', 'xls');
        $upload->savePath = './upload/';
        if (!$upload->upload()) {
            $err = $upload->getErrorMsg();
            $this->error($err);
        } else {// 上传成功
            $data = $upload->getUploadFileInfo();
            $filename = $data[0]['savepath'] . $data[0]['savename'];
            $exceldata = $this->excel->excel2Arr($filename);
            array_shift($exceldata);
            foreach($exceldata as $k=>$v){
                $model = M('device_info');
                $scan_code = $model->where(array('scan_code'=>$v[0],'del_flag'=>0))->find();
                if($scan_code){
                    $this->error('扫码码'.$v[0].'已存在,请删除这个扫描码，再上传');
                    exit;
                }
                $user_id = M('sys_user')->where(array('name'=>trim($v[1]),'del_flag'=>0))->find();
                if(!$user_id){
                    $this->error('归属用户'.$v[1].'不存在');
                    exit;
                }
                //归属部门
                $off_id = M('sys_office')->where(array('name'=>trim($v[2]),'del_flag'=>0))->find();
                if(!$off_id){
                    $this->error('归属部门'.$v[2].'不存在');
                    exit;
                }
                //归属公司
                $off_ides = M('sys_office')->where(array('name'=>trim($v[3]),'del_flag'=>0))->find();
                if(!$off_ides){
                    $this->error( '归属公司'.$v[3].'不存在');
                    exit;
                }
                //归属地区
                $off_area = M('sys_area')->where(array('name'=>trim($v[4]),'del_flag'=>0))->find();
                if(!$off_area){
                    $this->error('归属地区'.$v[4].'不存在');
                    exit;
                }
                $device_code = $model->where(array('device_code'=>trim($v[5]),'del_flag'=>0))->find();
                if($device_code){
                    $this->error( '硬件设备码'.$v[5].'已存在');
                    exit;
                }
                $device_command = $model->where(array('device_command'=>trim($v[6]),'del_flag'=>0))->find();
                if($device_command){
                    $this->error('硬件设备码'.$v[6].'已存在');
                    exit;
                }
                $device_type = '';
                if($v[7] == '娃娃机'){
                    $device_type = 1;
                }elseif($v[7] == '按摩椅'){
                    $device_type = 4;
                }elseif($v[7] == '充电器'){
                    $device_type = 2;
                }elseif($v[7] == '售货机'){
                    $device_type = 3;
                }elseif($v[7] == '洗衣机'){
                    $device_type = 5;
                }elseif($v[7] == '电动车充电'){
                    $device_type = 6;
                }elseif($v[7] == '洗车'){
                    $device_type = 7;
                }elseif($v[9] == '眼保仪'){
                    $device_type = 9;
                }
                $res['id'] = generateNum();
                $res['scan_code'] = trim($v[0]);
                $res['owner_id'] = $user_id['id'];
                $res['office_id'] = $off_id['id'];
                $res['company_id'] = $off_ides['id'];
                $res['area_id'] = $off_area['id'];
                $res['install_date'] = date('Y-m-d H:i:s',time());
                $res['install_type'] = 1;
                $res['device_code'] = trim($v[5]);
                $res['device_command'] = trim($v[6]);
                $res['device_type'] = $device_type;
                $res['device_status'] = 1;
                $res['pay_price'] = trim($v[7]);
                $res['create_by'] = $user_id['id'];
                $res['create_date'] = date('Y-m-d H:i:s',time());
                $res['update_date'] = date('Y-m-d H:i:s',time());
                $res['update_by'] = $user_id['id'];
                M('device_info')->add($res);
            }
            $this->success('添加成功',U('Device/index'));
            exit;
        }
    }
    //批量安装设备
    public function export(){
        $upload = new \UploadFile();
        $upload->maxSize = 8145728;
        $upload->allowExts = array('xlsx', 'xls');
        $upload->savePath = './upload/';
        if (!$upload->upload()) {
            $err = $upload->getErrorMsg();
            $this->error($err);
        } else {// 上传成功
            $data = $upload->getUploadFileInfo();
            $filename = $data[0]['savepath'] . $data[0]['savename'];
            $exceldata = $this->excel->excel2Arr($filename);
            array_shift($exceldata);
            foreach ($exceldata as $ke => $v) {
                if (stristr(trim($v[0]), "'")) {
                    $command = str_replace("'", '', trim($v[0]));
                    echo 1;
                } else {
                    $command = trim($v[0]);
                }
                $office_name = trim($v['3']);
                $office_id = M('sys_office')->where(array('name'=>$office_name,'del_flag'=>0))->getField('id');
                //echo preg_replace('/( |　|\s)*/','',$office_name);die;
                //echo M('sys_office')->getLastSql();p($office_id);die;
                if(!$office_id){
                    $this->error('部门不存在,或者存两边存在空格，请删除两边空格后在操作');
                    exit;
                }
                $owner_id = M('sys_user')->where(array('office_id'=>$office_id,'del_flag'=>0))->getField('id');
                $relation = M('device_relation_group')->where(array('del_flag' => 0, 'device_command' =>$command))->find();
                if($relation){
                    $this->error( '设备'.$command.'已经安装，请勿重复安装');
                    exit;
                }
                $model = M('device_info');
                $model->startTrans();
                $offid = $model->where(array('del_flag' => 0, 'device_command' =>$command))->find();
                if (!$offid) {
                    $this->error(array('code' =>201, 'msg' => '安装失败,硬编码' .$command . '不存在,请在后台上传硬编码'));
                    exit;
                }
                //查找群组名称
                $group_ided = M('deivce_group_info')
                    ->where("concat(',', group_name, ',') like '%," . $v[2] . ",%'  and office_id='$office_id'")
                    ->find();
                if (!$group_ided) {
                    /*$this->ajaxReturn(array('code' => 201, 'msg' => '安装失败,群组名称' . $v[2] . '不存在,请核对是否安装员有这个群组名称'));
                    exit;*/
                    $owner['id'] = generateNum();
                    $owner['owner_id'] = $owner_id;
                    $owner['group_name'] = $v[2];
                    $owner['office_id'] = $office_id;
                    $owner['ords'] = rand(1,99);
                    $owner['create_by'] = $office_id;
                    $owner['update_by'] = $office_id;
                    $owner['create_date'] = date('Y-m-d H:i:s',time());
                    $owner['update_date'] = date('Y-m-d H:i:s',time());
                    $ui = M('deivce_group_info')->add($owner);
                }
                $group_id = M('deivce_group_info')
                    ->where(array('group_name'=>$v[2],'office_id'=>$office_id))
                    ->find();
                $dataes['device_status'] = 1;
                $dataes['update_date'] = date('Y-m-d H:i:s',time());
                $dataes['install_type'] = 1;
                $rese = $model->where(array('del_flag' => 0, 'device_command' =>$command))->save($dataes);

                //$device_command = $model->where(array('device_command' => $command, 'status' => 1, 'del_flag' => 0))->find();
                $p_array = array(
                    'A' => '1', 'B' => '2', 'C' => '3', 'D' => '4', 'E' => '5',
                    'F' => '6', 'G' => '7', 'H' => '8', 'I' => '9', 'J' => '10',
                    'K' => '11', 'L' => '12', 'M' => '13', 'N' => '14', 'O' => '15',
                    'P' => '16', 'Q' => '17', 'R' => '18', 'S' => '19', 'T' => '20',
                    'U' => '21', 'V' => '22', 'W' => '23', 'X' => '24', 'Y' => '25', 'Z' => '26'
                );
                $ords = '';
                if (array_key_exists($v[1], $p_array)) {
                    $ords = $p_array[$v[1]];
                } else {
                    $ords = rand(1,99);
                }
                $res['id'] = md5(uniqid());
                $res['dgi_id'] = $group_id['id'];
                if($offid['device_type'] == 5){
                    $res['charger'] = '1=5-2=18-3=31-4=43';
                }elseif($offid['device_type'] == 4){
                    $res['ANM'] = '1=5-2=18-3=31';
                }elseif($offid['device_type'] == 6){
                    $res['charger'] = '1=5-2=18-3=31';
                }elseif($offid['device_type'] == 7){
                    $res['charger'] = '7=10';
                }elseif($offid['device_type'] == 9){
                    $res['charger'] = '10-1800=10-1800=10-1800=10-1800';
                }
                $res['group_word'] = $v[1];
                $res['di_id'] = $offid['id'];
                $res['device_code'] = $offid['device_code'];
                $res['device_command'] = $offid['device_command'];;
                $res['device_type'] = $offid['device_type'];
                $res['ANM'] = $offid['ANM'];
                $res['update_by'] = $office_id;
                $res['create_by'] = $office_id;
                $res['ords'] = $ords;
                $res['status'] = 1;
                $res['create_date'] = date('Y-m-d H:i:s',time());
                $res['update_date'] = date('Y-m-d H:i:s',time());
                $id = M('device_relation_group')->add($res);
                if($rese && $id){
                    $model->commit();
                } else {
                    $model->rollback();
                }
            }
            $this->success('安装成功',U('GroupDetail/index'));
        }
    }
}
