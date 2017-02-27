<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Poststatus;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建文章', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //去掉序列号 ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'id',
                'contentOptions' => ['width' => '30px']
            ],
            'title',
            [
                'attribute' => 'author_name',
                'label' => '作者',
                'value' => 'author.nickname',
            ],
            //太长了不要了 'content:ntext',
            'tags:ntext',
            //'status',
            [
                'attribute' => 'status',
                /*'value'=> function ($model) {
                    return $model->status == 1?'已发布':'未发布';
                }*/
                'value' => 'status0.name',
                'filter' => Poststatus::find()
                    ->select(['name', 'id'])
                    ->orderBy('position')
                    ->indexBy('id')
                    ->column(),
            ],
            //'created_at',
            //'updated_at',
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                //'value'=>date("Y-m-d H:i:s", $model->updated_at);
            ],
            // 'author_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
