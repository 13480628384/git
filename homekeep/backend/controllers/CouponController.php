<?php

namespace backend\controllers;

use Yii;
use backend\models\Coupon;
use backend\models\CouponSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\shop;
/**
 * CouponController implements the CRUD actions for Coupon model.
 */
class CouponController extends Controller
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

    /**
     * Lists all Coupon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CouponSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Coupon model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Coupon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Coupon();
        //查询出所有有效的商品id
        $shop = Shop::find()->select('id,title')->where(['status'=>'1','online'=>'1'])->all();
        //echo Yii::$app->user->identity->id;die;
        if ($model->load(Yii::$app->request->post())) {
            $string = Yii::$app->request->post()[ucfirst(Yii::$app->controller->id)]['range'];
            $str_ran = implode(',',$string);
            $model->range = $str_ran;
            $model->create_by = Yii::$app->user->identity->id;
            $model->term_time = Yii::$app->request->post()[ucfirst(Yii::$app->controller->id)]['term_time'].' 00:00:00';
            $model->create_date = date('Y-m-d H:i:s',time());
            $model->update_date = date('Y-m-d H:i:s',time());
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'shop_id' => $shop
        ]);
    }

    /**
     * Updates an existing Coupon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $shop = Shop::find()->select('id,title')->where(['status'=>'1','online'=>'1'])->all();
        if ($model->load(Yii::$app->request->post())) {
            $string = Yii::$app->request->post()[ucfirst(Yii::$app->controller->id)]['range'];
            $str_ran = implode(',',$string);
            $model->range = $str_ran;
            $model->create_by = Yii::$app->user->identity->id;
            $model->term_time = Yii::$app->request->post()[ucfirst(Yii::$app->controller->id)]['term_time'].' 00:00:00';
            $model->update_date = date('Y-m-d H:i:s',time());
            $model->save();
            return $this->redirect(['index']);
        }
        $model->range = explode(',',$model->range);
        return $this->render('update', [
            'model' => $model,
            'shop_id' => $shop,
        ]);
    }

    /**
     * Deletes an existing Coupon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Coupon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Coupon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Coupon::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
