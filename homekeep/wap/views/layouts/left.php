<?php
use yii\helpers\Html;
use yii\helpers\Url;
use mdm\admin\components\MenuHelper;
$callback = function($menu){
    $data = json_decode($menu['data'], true);
    $items = $menu['children'];
    $return = [
        'label' => $menu['name'],
        'url' => [$menu['route']],
    ];
    //处理我们的配置
    if ($data) {
        //visible
        isset($data['visible']) && $return['visible'] = $data['visible'];
        //icon
        isset($data['icon']) && $data['icon'] && $return['icon'] = $data['icon'];
        //other attribute e.g. class...
        $return['options'] = $data;
    }
    //没配置图标的显示默认图标，默认图标大家可以自己随便修改
    (!isset($return['icon']) || !$return['icon']) && $return['icon'] = 'circle-o';
    $items && $return['items'] = $items;

    return $return;
};
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php
                    /*if(!Yii::$app->user->isGuest){
                        echo Yii::$app->user->identity->username;
                    }*/
                    ?></p>
            </div>
        </div>
        <ul class="sidebar-menu tree" data-widget="tree">
            <?= dmstr\widgets\Menu::widget( [
                'options' => ['class' => 'sidebar-menu'],
                'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id),
            ] );
            ?>
        </ul>
    </section>

</aside>
