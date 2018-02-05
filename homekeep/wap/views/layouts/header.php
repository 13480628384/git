<?php
use yii\helpers\Html;
/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?php
                            if(!Yii::$app->user->isGuest){
                                echo Yii::$app->user->identity->username;
                            }
                            ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <small><?php
                                    /* if(!Yii::$app->user->isGuest){
                                    echo  Yii::$app->user->identity->email;}*/?></small>
                            </p>
                            <p><?php /*if(!Yii::$app->user->isGuest){
                                echo date('Y年m月d日 H:i:s',Yii::$app->user->identity->updated_at);
                                }*/?></p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <?php if(!Yii::$app->user->isGuest){
                                    echo Html::a(
                                        '退出',
                                        ['/site/logout'],
                                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                    ); }
                                ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!--
                   <li>
                       <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                   </li>-->
            </ul>
        </div>
    </nav>
</header>