<?php
class RoadnextAction extends BaseAction
{
    public function uploadpic(){
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize = 512000;// 设置附件上传大小
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');//设置上传文件类型
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->saveRule = "image_".time();
        $upload->savePath = __ROOT__ . 'upload/' . $this->token . '/image/' . date('Ymd') . '/';// 设置附件上传目录
        if (!$upload->upload()) {    // 上传错误提示错误信息
            $this->error($upload->getErrorMsg());
        } else {                      // 上传成功 获取上传文件信息
            $info = $upload->getUploadFileInfo();
            exit(json_encode($info));
        }
    }
    public function add_img(){
        if(IS_POST){
            $_POST['imgs'] = urldecode($_POST['imgs']);
            $img=explode(',',$_POST['imgs']);
            $access_token  = $this->getAccessToken();
            //目录
            $dir="./upload/weixin_imgs/".date('Y',time())."/".date('m',time())."/".date('d',time());
            if(!is_dir($dir)){
                mkdir($dir,0777,true);
            }
            $urls=array();
            foreach($img as $v){
                $mediaid=$v;
                $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
                $fileInfo = downloadWeixinFile($url);
                $filename = $dir."/".getSn().".jpg";//取名字
                saveWeixinFile($filename, $fileInfo["body"]);
                //$urls['imgs'][] = $filename;
                /*$new_url = "./upload/weixin/";
                if(!is_dir($new_url)){
                    mkdir($new_url,0777,true);
                }
                $filesize = filesize($filename);
                if($filesize>1048576){
                    $this->image_png_size_add($filename,$new_url);
                }*/
                $aliImage  = new AlibabaImage($this->ak,$this->sk);
                $uploadPolicy = new UploadPolicy( $this->namespace);
                $uploadPolicy->dir = 'guangjia';
                $res = $aliImage->upload( $filename, $uploadPolicy );
                $urls['imgs'][] = $res['url'];
                @unlink ($filename);
            }
            echo json_encode($urls);
        }
    }
    function getAccessToken(){
            $url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->APP_ID.'&secret='.$this->APP_SECRET;
            $json=json_decode(file_get_contents($url_get));
            if(!isset($json->access_token)){
                return false;
            }else{
                return $json->access_token;
            }
    }
    /**
     * desription 压缩图片
     * @param sting $imgsrc 图片路径
     * @param string $imgdst 压缩后保存路径
     */
    function image_png_size_add($imgsrc,$imgdst){
        list($width,$height,$type)=getimagesize($imgsrc);
        $new_width = ($width>1200?600:$width)*0.9;
        $new_height =($height>1200?600:$height)*0.9;
        switch($type){
            case 1:
                $giftype=$this->check_gifcartoon($imgsrc);
                if($giftype){
                    header('Content-Type:image/gif');
                    $image_wp=imagecreatetruecolor($new_width, $new_height);
                    $image = imagecreatefromgif($imgsrc);
                    imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                    imagejpeg($image_wp, $imgdst,75);
                    imagedestroy($image_wp);
                }
                break;
            case 2:
                header('Content-Type:image/jpeg');
                $image_wp=imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromjpeg($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($image_wp, $imgdst,75);
                imagedestroy($image_wp);
                break;
            case 3:
                header('Content-Type:image/png');
                $image_wp=imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefrompng($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($image_wp, $imgdst,75);
                imagedestroy($image_wp);
                break;
        }
    }
    /**
     * desription 判断是否gif动画
     * @param sting $image_file图片路径
     * @return boolean t 是 f 否
     */
    function check_gifcartoon($image_file){
        $fp = fopen($image_file,'rb');
        $image_head = fread($fp,1024);
        fclose($fp);
        return preg_match("/".chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0'."/",$image_head)?false:true;
    }

    public function update_per_img(){
        if(IS_POST){
            $_POST['imgs'] = urldecode($_POST['imgs']);
            $img=explode(',',$_POST['imgs']);
            $access_token  = $this->getAccessToken();
            //目录
            $dir="./upload/weixin_imgs/".date('Y',time())."/".date('m',time())."/".date('d',time());
            if(!is_dir($dir)){
                mkdir($dir,0777,true);
            }
            $urls=array();
            foreach($img as $v){
                $mediaid=$v;
                $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
                $fileInfo = downloadWeixinFile($url);
                $filename = $dir."/".getSn().".jpg";//取名字
                saveWeixinFile($filename, $fileInfo["body"]);
                $aliImage  = new AlibabaImage($this->ak,$this->sk);
                $uploadPolicy = new UploadPolicy( $this->namespace);
                $uploadPolicy->dir = 'guangjia';
                $res = $aliImage->upload( $filename, $uploadPolicy );
                $urls['imgs'][] = $res['url'];
                @unlink ($filename);
            }
            echo json_encode($urls);
        }
    }
}