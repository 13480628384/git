<?php
/*
 * 文件基础操作类
 * author lihuasheng
 * 2015-07-04 来自tp本地上传驱动
 */
class FileLocal{
    /**
     * 文件文件根目录
     * @var string
     */
    private $rootPath;


    /**
     * 构造函数，用于设置文件根路径
     */
    public function __construct(){
    }

    /**
     * 检测文件根目录
     * @param string $rootpath   根目录
     * @return boolean true-检测通过，false-检测失败
     */
    public function checkRootPath($rootpath){
        if(!(is_dir($rootpath) && is_writable($rootpath)) && !$this->mkdir($rootpath)){
            throw new \Exception('文件保存根目录不存在！请手动创建：'.$rootPath,-8001);
            return false;
        }
        $this->rootPath = $rootpath;
        return true;
    }

    /**
     * 检测保存目录
     * @param  string $savepath 文件目录
     * @return boolean          检测结果，true-通过，false-失败
     */
    public function checkSavePath($savepath){
        /* 检测并创建目录 */
        if (!$this->mkdir($savepath)) {
            return false;
        } else {
            /* 检测目录是否可写 */
            if (!is_writable($savepath)) {
                throw new \Exception('文件目录'.$savepath.'不可写！',-8002);
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * 保存指定文件适用于上传
     * @param file 文件信息
     * @param  array   $filename    保存的文件路径
     * @param  boolean $replace 同名文件是否覆盖
     * @return boolean          保存状态，true-成功，false-失败
     */
    public function save($file,$filename,$replace=true) {
        /* 不覆盖同名文件 */
        if (!$replace && is_file($filename)) {
            throw new \Exception('存在同名文件'.$file['savename'],-8003);
            return false;
        }
        /* 移动文件 */
        if (!move_uploaded_file($file['tmp_name'], $filename)) {
            throw new \Exception('文件保存错误！',-8004);
            return false;
        }

        return true;
    }

    /**
     * 创建目录
     * @param  string $savepath 要创建的目录
     * @return boolean          创建状态，true-成功，false-失败
     */
    public function mkdir($savepath){
        $dir = $savepath;
        if(is_dir($dir)){
            return true;
        }

        if(mkdir($dir, 0777, true)){
            return true;
        } else {
            throw new \Exception('文件目录'.$savepath.'创建失败！',-8005);
            return false;
        }
    }
    /**
     * 检查文件大小是否合法
     * @param integer $size 数据
     */
    private function checkSize($size) {
        return !($size > $this->maxSize) || (0 == $this->maxSize);
    }
    /*
     * 删除文件
     */
    public function delFile($filename){
        /* 检测文件是否存在 */
        if (!is_file($filename)) {
            throw new \Exception('文件'.$file['savename'].'不存在！',-8003);
            return false;
        }else{
            if(unlink($filename)){
           return true;
            }else{
            throw new \Exception('文件删除失败，权限不足');
            }
        }
    }
}
