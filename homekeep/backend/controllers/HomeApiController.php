<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Wechat;
/**
 * CouponController implements the CRUD actions for Coupon model.
 */
class HomeApiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function actionIndex(){
        $options = array(
            'token'=>'9HbtghebeZ9XhxH39g339JEGg45rfg34',
            'encodingaeskey'=>'KyXa5vaKUNjEhVpTJoP5UaexqtbtV0tdARWkTSXnkfk',
            'appid'=>'wxee8daf7e86a4c6fc',
            'appsecret'=>'c85b3180a580e7f58beb56d630c1f8b6',
            'agentid'=>'1',
            'debug'=>true,
        );
        $weObj = new Wechat($options);
        $weObj->valid();
        $type = $weObj->getRev()->getRevType();
        if($type == 'event'){
            $text = '你好，欢迎来到红家君助!';
            $weObj->text($text)->reply();
        }
    }
}
