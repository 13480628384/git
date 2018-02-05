<?php
namespace Common\Model;
use Common\Model\CommonModel;
class Device_relation_groupModel extends CommonModel{
    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('full_name', 'check_full_name', '姓名不能为空！', 1, 'callback', CommonModel:: MODEL_INSERT  ),
        //array('email', 'check_full_email', '邮箱不能为空！', 1, 'callback', CommonModel:: MODEL_INSERT ),
        array('content', 'require', '评论不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        //array('email','email','邮箱格式不正确！',0,'',CommonModel:: MODEL_BOTH ),
        array('post_table','table_exists','您评论的内容不存在！',0,'callback',CommonModel:: MODEL_BOTH ),
    );
    function mDate(){
        return date("Y-m-d H:i:s");
    }

}