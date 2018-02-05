<?php
header("Content-Type:text/html;charset=utf-8");
class Mysql{
    private $comm = ''; #数据库连接标识符
    private $db   = ''; #数据库名称
    private $user = ''; #用户名
    private $pwd  = ''; #密码
    private $host = ''; #地址
    private $char = ''; #编码

    #构造方法 , 初始化参数
    public function __construct($host,$user,$pwd,$db,$char='utf8'){
        $this->host = $host;
        $this->user = $user;
        $this->pwd  = $pwd;
        $this->db   = $db;
        $this->char = $char;
        $this->conn(); # 连接数据库
    }

    #连接mysql,并选择数据库和编码
    private function conn(){
        $this->conn = @mysql_connect($this->host,$this->user,$this->pwd) or exit('连接失败');
        $this->query('use '.$this->db);
        $this->query('set names '.$this->char);
    }

    public function query($sql){
        $reqult = mysql_query($sql,$this->conn);
        mysql_close($this->conn);
        return $reqult;
    }
    public function close()
    {
        mysql_close($this->conn);
    }
    public function get_all($sql){
        $this->conn();
        $data = array();
        $result = $this->query($sql);
        #获取数据 mysql_fetch_object();
        while($row = mysql_fetch_assoc($result)){
            $data[] = $row;
        }
        $this->close();
        return $data;
    }
    public  function p($arr,$type='print_r'){
        echo '<pre>';
        $type($arr);
        echo '</pre>';
    }
    /**
     * fucntion 查询一条数据
     * param    string        $sql          查询语句
     * return   array         $data         查询的数据[]
     */
    public function get_one($sql){
        $this->conn();
        $result = $this->query($sql);
        $data = mysql_fetch_assoc($result);
        $this->close();
        return $data;
    }

    /**
     * 判断操作结果函数
     */
    private function res($result){
        if($result && mysql_affected_rows()>0){
            $this->close();
            return true;
        }else{
            $this->close();
            return false;
        }
    }

    /**
     * fucntion   删除指定条件的数据
     * param      string    $table           表名
     * param      string    $where           条件
     * return     boolean                    执行结果 [true/false]
     */
    public function delete($table,$where){
        $this->conn();
        $sql = "DELETE FROM `$table` WHERE $where";
        //$this->p($sql);die;
        $result = $this->query($sql);
        return $this->res($result);
    }

    /**
     * function 添加数据
     * param  string    $table  要添加数据的表名
     * param  array     $data   要添加的数据数组[字段名为数组的键, 字段值为数组的值]
     * return boolean   true/false
     **/
    public function add($table,$data){
        #使用数组函数 array_keys和array_values进行SQL拼接
        $sql = "INSERT INTO `$table` (`";
        $sql .= implode('`,`',array_keys($data)); #获取数组的键
        $sql .= "`) VALUES ('";
        $sql .= implode("','",array_values($data)) ."')"; ##获取数组的值
        $this->conn();
        $result = $this->query($sql);
        return $this->res($result);
    }

    /**
     * function 数据更新函数
     * param    string      $table        表名
     * param    array       $data         修改的数据
     * param    string      $where        条件
     * return   boolean                   执行的结果[true/false]
     */

    public function edit($table,$data,$where){
        $sql = "update `$table` SET ";
        foreach($data as $key=>$value){
            $sql .="`$key` = '$value',";
        }
        $sql = rtrim($sql,','); #rtrim 去掉右边指定的字符
        $sql .=' WHERE '.$where;
        $this->conn();
        $result = $this->query($sql);
        return $this->res($result);
    }
    /**
     * 字符串截取
     * @param string $str  输入被截取的字符串
     * @return string 返回一个字符串
     */
    public function my_substr($str,$num=15){
        if(mb_strlen($str,'utf-8')>$num){
            return mb_substr($str,0,$num,'utf-8').'...';
        }else{
            return $str;
        }
    }
    /**
     * @param $msg string 提示信息
     * @param $url string 跳转的页面
     */
    public function show_msg($msg,$url=''){

        echo '<script>';
        echo 'alert("'.$msg.'");';
        if(empty($url)){
            echo 'window.history.go(-1);';
        }else{
            echo 'window.location.href="'.$url.'"';
        }
        echo '</script>';
        exit();
    }
    /**
     * @param $filename string  上传文件框的name值
     * @param $path  string 上传文件保存路径
     * @param $type  array  上传文件的类型
     * @param $size  int    上传文件的最大值
     * @return  如果是一个数组，表示上传成功，返回保存的文件路径，文件名
     * 如果是一个字符串，表示错误信息。
     */
    public  function uploads($name,$type,$size,$path){
        $file = $_FILES[$name];
        # 1. 判断当前文件是否是post过来的文件  is_uploaded_file();
        if(!is_uploaded_file($file['tmp_name'])){
            return array('上传文件错误!');
        }
        # 2. 上传文件的错误状态判断 只有 error为0 的时候我们才会做文件上传处理
        if($file['error'] == 1 ){
            return array('上传文件太大!');
        }else if($file['error'] == 2 ){
            return array('上传文件太大!');
        }else if($file['error'] == 3 ){
            return array('上传文件不完整!');
        }else if($file['error'] == 4 ){
            return array('没有找到上传文件');
        }else if($file['error'] >4 ){
            return array('上传文件发生了未知错误,请联系网站工作人员!');
        }

        # 上传文件的类型判断

        #获取文件后缀
        $pre = pathinfo($file['name'],PATHINFO_EXTENSION);

        if(!in_array( $pre,$type) ){
            return array('上传文件的类型不正确!');
        }

        # 对上传文件大小进行判断
        if ($file['size'] > $size ){
            return array('上传文件太大!');
        }

        # 移动上传文件到我们的目录里面去
        # move_uploaded_file();
        # 为了防止上传文件的重命名，建议使用 微秒时间戳加上 随机数

        $newname = date('YmdHis',time()).mt_rand(10000,99999);
        $res = move_uploaded_file($file['tmp_name'],$path.'/'.$newname.'.'.$pre);
        if($res){
            return array('上传文件成功',$newname.'.'.$pre);
        }else{
            return array('上传文件失败');
        }
    }
    //时间
    public   function tranTime($time) {
        $rtime = date("m-d H:i",$time);
        $htime = date("H:i",$time);
        $time = time() - $time;
        if($time < 60) {
            $str = '刚刚';
        }
        else if($time < 60 * 60) {
            $min = floor($time/60);
            $str = $min.'分钟前';
        } else if($time < 60 * 60 * 24) {
            $h = floor($time/(60*60)); $str = $h.'小时前 '.$htime;
        }else if($time < 60 * 60 * 24 * 3) {
            $d = floor($time/(60*60*24));
            if($d==1){$str = '昨天 '.$rtime;}
            else {$str = '前天 '.$rtime;}
        }else {$str = $rtime;
        }
        return $str;
    }
    public function thumb($img,$s_width,$s_height,$prefix="_min",$path="./"){
        #1. 打开源图(1) 获取图片大小
        $info = getimagesize($img);
        # print_r($info); #要记得$info的前三个成员
        #2. 打开原图(2) 根据不同的类型使用对应函数打开源图资源
        # $b_res 表示源图资源
        switch($info[2]){
            case 1:
                $b_res = imagecreatefromgif($img);
                break;
            case 2:
                $b_res = imagecreatefromjpeg($img);
                break;
            case 3:
                $b_res = imagecreatefrompng($img);
                break;
        }
        #3. 新建一个真彩色的画布[缩略图的资源]
        $s_res = imagecreatetruecolor($s_width,$s_height);
        #4. 把源图按比例复制小图上(10参数)
        imagecopyresized($s_res,$b_res,0,0,0,0,$s_width,$s_height,$info[0],$info[1]);
        #5. 保存缩略图
        switch($info[2]){
            case 1:
                imagegif($s_res,$path.$prefix.basename($img));
                break;
            case 2:
                imagejpeg($s_res,$path.$prefix.basename($img));
                break;
            case 3:
                imagepng($s_res,$path.$prefix.basename($img));
                break;
        }
        #6. 关闭图片资源
        imagedestroy($b_res);
        imagedestroy($s_res);
    }
}

?>