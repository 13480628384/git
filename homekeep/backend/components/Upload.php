<?php
namespace backend\components;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\base\Exception;
use yii\helpers\FileHelper;

class Upload extends Model
{
    public $img;
    private $_appendRules;
    public function init ()
    {
        parent::init();
        $extensions = Yii::$app->params['webuploader']['baseConfig']['accept']['extensions'];
        $this->_appendRules = [
            [['img'], 'file', 'extensions' => $extensions],
        ];
    }

    public function rules()
    {
        $baseRules = [];
        return array_merge($baseRules, $this->_appendRules);
    }

    /**
     * [UploadPhoto description]
     * @param [type]  $model      [实例化模型]
     * @param [type]  $path       [图片存储路径]
     * @param [type]  $originName [图片源名称]
     * @param boolean $isthumb    [是否要缩略图]
     */
    /*public function UploadPhoto($model,$path,$originName,$isthumb=false){
        $root = getcwd().'/'.$path;
        //返回一个实例化对象
        $files = UploadedFile::getInstance($model,$originName);
        $folder = date('Ymd')."/";
        $pre = rand(999,9999).time();
        if($files && ($files->type == "image/jpeg" || $files->type == "image/pjpeg" || $files->type == "image/png" || $files->type == "image/x-png" || $files->type == "image/gif"))
        {
            $newName = $pre.'.'.$files->getExtension();
        }else{
            die($files->type);
        }
        if($files->size > 2000000){
            die("上传的文件太大");
        }
        if(!is_dir($root.$folder))
        {
            if(!mkdir($root.$folder, 0777, true)){
                die('创建目录失败...');
            }else{
                //	chmod($root.$folder,0777);
            }
        }
        //echo $root.$folder.$newName;exit;
        if($files->saveAs($root.$folder.$newName))
        {
            return dirname(dirname('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])).'/'.$path.$folder.$newName;
        }
    }*/
    /**
     *
     */
    public function upImage ()
    {
        $model = new static;
        $model->img = UploadedFile::getInstanceByName('file');
        if (!$model->img) {
            return false;
        }
        $relativePath = $successPath = '';
        if ($model->validate()) {
            $relativePath = Yii::$app->params['imageUploadRelativePath'];
            $successPath = Yii::$app->params['imageUploadSuccessPath'];
            $fileName = $model->img->baseName . '.' . $model->img->extension;
            if (!is_dir($relativePath)) {
                FileHelper::createDirectory($relativePath);
            }
            $model->img->saveAs($relativePath . $fileName);
            return [
                'code' => 0,
                'url' => Yii::$app->params['domain'] . $successPath . $fileName,
                'attachment' => $successPath . $fileName
            ];
        } else {
            $errors = $model->errors;
            return [
                'code' => 1,
                'msg' => current($errors)[0]
            ];
        }
    }
}