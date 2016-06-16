<?php
class ChangeAction extends BaseAction
{
//检查是否存在硬编码---更换硬编码
    public function update_hard_device_code_exists()
    {
        if (empty($this->user_id)) {
            exit;
        }
        $model = M('ter_device');
        $model->startTrans();
        $device_command = $_POST['device_command'];
        $res = $model->where(array('device_command' => $device_command, 'del_flag' => 0))->find();
        $res1 = M('device_group')->where(array('device_command' => $device_command, 'del_flag' => 0, 'status' => 1))->find();
        //$res2 = M('weixin_qcode')->where(array('device_code' => $device_command))->find();
        if ($res && $res1) {
            $model->commit();
            echo json_encode(array('msg' => 1, 'datas' => $res));
        } else {
            $model->rollback();
            echo json_encode(array('msg' => 2));
        }
    }
    //修改坏设备
    public function change_command(){
        $this->display();
    }
    public function update_hpcode(){
        if(!isset($this->user_id)){
            exit;
        }
        $model = M('ter_device');
        $model->startTrans();
        $id = $model->field('id')->where(array('device_command'=>$_POST['oldpcode'],'status'=>1,'del_flag'=>0))->find();//-----2016-03-29
        $where1['device_command'] = $_POST['newpcode'];
        $where2['device_id'] = $id['id'];
        $where2['device_code'] = $_POST['newpcode'];
        $res1 = $model->where(array('device_command'=>$_POST['oldpcode']))->save($where1);
        $res2 = M('device_group')->where(array('device_command'=>$_POST['oldpcode']))->save($where1);
        //$res3 = M('weixin_qcode')->where(array('device_code'=>$_POST['oldpcode']))->save($where2);
        //删除重复数据
        $wno = M('weixin_qcode')->where(array('status'=>1))->group('wno')->having("count('wno')>1")->find();
        $max = M('weixin_qcode')->where(array('status'=>1))->group('wno')->having("count('wno')>1")->max('id');
        $where3['wno'] = array('in',array($wno['wno']));
        $where3['id'] = array('not in',array($max));
        $weixin = M('weixin_qcode')->field('id')->where($where3)->select();
        $sum_array = array();
        $count = count($weixin);
        for($i = 0; $i < $count; $i++){
            $sum_array[] = $weixin[$i]['id'];
        }
        $id_all = implode(",",$sum_array);
        $where4['id'] = array('in',$id_all);
        $where4['device_code'] = $_POST['newpcode'];//-------2016-03-29

        if($res1 && $res2){
            $model->commit();
            $del['del_flag'] = 1;
            $res4 = M('weixin_qcode')->where($where4)->save($del);
            echo json_encode(array('msg'=>1,'datas'=>'恭喜你，更新成功'));
        }else{
            $model->rollback();
            echo json_encode(array('msg'=>2));
        }
    }
}
?>