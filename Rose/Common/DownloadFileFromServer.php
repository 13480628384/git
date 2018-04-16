<?php
/*
 * 文件获取类
 * 主要负责从微信端下载临时文件
 * author lihuasheng
 * 2015.7.4 第一版
 */
defined('DS') || define('DS', DIRECTORY_SEPARATOR);
class DownloadFileFromServer{
    private $header;#http响应头
    private $body;#http文件内容
    private $rootPath;#上传根目录
    static $fileType=array('image','video','voice','file');#预定义文件类型
    private $name='未知';#文件旧有名称
    private $type;#文件旧有类型，于微信端一致
    private $media_id;#媒体id
    private $token;#资源授权码
    private $openid;
    private $access_token;
    private $httpHeadArr;
    static  $exts=array(
        'image'=>array('bmp','jpg','jpeg','png','gif'),
        'video'=>array('avi','rmvb','rm','asf','divx','mpg','mpeg','mpe','wmv','mp4','mkv','vob'),
        'voice'=>array('amr','mp3','wma','mmf','mid'),
        'file'=>array()
    );
    public function __construct($token,$openid, $media_id,$rootPath){#构造函数
        /*
         * 严格检查
         */
        $this->openid   = $openid;
        $this->media_id = $media_id;#媒体id
        $this->token    = $token;#资源授权码
        if(empty($this->token)) throw new \Exception('缺少授权码！token='.$this->token,-8012);
        if(empty($this->media_id))
            throw new \Exception('缺少media_id!',8007);
        $this->rootPath     = $rootPath?$rootPath:'./upload/serverfile/';
        $this->File         = new FileLocal;
        $this->access_token = getAccessToken($token);
    }
    /*
     * 获得远程文件
     * @param 来自微信的返回信息，数组
     * @$parm $token临时授权码
     */
    public function getFile(){

        $file_url=sprintf('https://api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s', $this->access_token, $this->media_id);
        $timeout=60;#过期时间
        if( !function_exists("curl_init") &&
            !function_exists("curl_setopt") &&
            !function_exists("curl_exec") &&
            !function_exists("curl_close") )
            throw new \Exception('curl模块错误！',-8006);

        $ch = curl_init();#开始curl会话
        curl_setopt($ch, CURLOPT_URL, $file_url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, true);//http头
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);#内容做为变量存储
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $temp = curl_exec($ch);
        if (!curl_error($ch) && curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);#取得头长度
            $header = substr($temp, 0, $headerSize);#获得头内容
            $this->header=$header;
            $body = substr($temp, $headerSize);#获得文件内容

            if(curl_getinfo($ch,CURLINFO_SIZE_DOWNLOAD)<=100){#如果内容长度小于100进行错误检查
                $msg=json_decode($body,true);
                if(is_numeric($msg['errcode'])){
                    throw new \Exception($msg['errmsg'],-$msg['errcode']);
                }
            }
            $this->setHttpHeadArr();#组织http头信息
            $this->setFileType();#构建文件类型
            return $this->saveFile($body);
        }else{
            throw new \Exception(curl_error($ch),-8009);
        }
    }
    /*
     * 文件类型归类
     */
    public function setFileType(){
        $ext=$this->getExt();
        $exts=self::$exts;
        foreach($exts as $k=>$v){
            if(in_array($ext,$v)){
                $this->type=$k;
                break;
            }else{
                $this->type='file';
            }
        }
    }
    /*
     * 检查路径，并保存文件
     * @param $body  文件类型
     */
    private function saveFile($body){
        if($this->File->checkRootPath($this->rootPath)){
            $saveName=$this->getSaveName();
            $subPath=$this->getSubPath($saveName);
            $rootPath=$this->rootPath;
            $savePath=$rootPath.$subPath.DS;
            $fileName=$savePath.$saveName;
            if($this->File->checkSavePath($savePath)){
                $size=@file_put_contents($fileName,$body);
                if($size) {
                    $file['name']=$this->name;
                    $file['savename']=$saveName;
                    $file['savepath']=$subPath;
                    $file['real_path']=$this->rootPath . DS. $subPath . DS . $saveName;
                    $file['mime']=$this->getMime();
                    $file['ext']=$this->getExt();
                    $file['size']=$size;
                    $file['md5']=md5_file($fileName);
                    $file['sha1']=sha1_file($fileName);
                    $file['create_time']=time();
                    return $file;
                }else{
                    throw new \Exception('文件保存失败！',-8010);
                }
            }

        }
    }

    /*
     * 生成保存路径包括文件名
     */
    private function getSaveName() {
        $d=$this->getExt();
        $h=uniqid();
        $name=$h.'.'.$d;
        return $name;
    }
    /*
     * 给出保存子路径
     * @param $name文件保存名称
     */
    private function getSubPath($name){
        $path=$this->type.DS.date('Ym',time()).DS.date('d',time());
        return $path;
    }

    /**
     * 检查上传的文件后缀是否合法
     * @param string $ext 后缀
     */
    private function checkExt($ext) {
        return empty($this->config['exts']) ? true : in_array(strtolower($ext), $this->exts);
    }

    /*
     * 根据请求头获得文件后缀
     * return
     */
    private function getExt(){
        if(!empty($this->httpHeadArr)){
            $data=$this->httpHeadArr;
            $len = strrpos($data['Content-disposition'], '"') - strrpos($data['Content-disposition'], '.');
            $ext=substr($data['Content-disposition'],strrpos($data['Content-disposition'], '.')+1, $len-1);
            $ext=strtolower($ext);
            return $ext;
        }else{
            $this->setHttpHeadArr();
            $this->getExt();
        }
    }
    /*
     * 获得文件mime类型
     */
    private function getMime(){
        if(!empty($this->httpHeadArr)){
            $data=$this->httpHeadArr;
            return trim($data['Content-Type']);
        }else{
            $this->setHttpHeadArr();
            $this->getMime();
        }
    }

    /*
     * 将http头转化为数组存入成员变量
     */
    private function setHttpHeadArr(){
        if(empty($this->header)) throw new \Exception('没有响应信息！',-8011);
        $headerArr=explode(PHP_EOL,$this->header);
        foreach($headerArr as $k=>$v){
            $arr=explode(':',$v);
            if($arr[1]){
                $headArr[$arr[0]]=$arr[1];
            }
        }
        $this->httpHeadArr=$headArr;
    }
}
