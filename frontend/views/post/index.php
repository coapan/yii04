<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <?= ListView::widget([
                'id' => 'postList',
                'dataProvider' => $dataProvider,
                'itemView' => '_postlist', //子视图，显示文章
                'layout' => '{items} {pager}',
                'pager' => [
                    'maxButtonCount' => 10,
                    'nextPageLabel' => Yii::t('app', '下一页'),
                    'prevPageLabel' => Yii::t('app', '上一页'),
                ],
            ]) ?>
        </div>

        <div class="col-md-3">
            <div class="searchbox">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span> 查找文章 (
                        <?php echo \common\models\Post::find()->select('id')->count();?>

                        )
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>