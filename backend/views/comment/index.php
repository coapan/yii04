<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Commentstatus;
use yii\grid\Column;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评论列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p>
        <? /*= Html::a('添加评论', ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'id',
                'contentOptions' => ['width' => '30px'],
            ],
            //'content:ntext',
            [
                'attribute' => 'content',
                'value' => 'beginning',
            ],
            [
                'attribute' => 'user_id',
                'label' => '用户名',
                'value' => 'user.username',
                //'contentOptions'=>['width'=>'55px'],
            ],
            //'status',
            [
                'attribute' => 'status',
                'value' => 'status0.name',
                'filter' => Commentstatus::find()
                    ->select(['name', 'id'])
                    ->orderBy('position')
                    ->indexBy('id')
                    ->column(),
                'contentOptions' => function ($model) {
                    return ($model->status == 2) ? ['class' => 'btn btn-danger',] : [];
                },
            ],
            //'created_at',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:m-d H:i'],
            ],
            //'user_id',
            // 'email:email',
            // 'url:url',
            // 'post_id',
            [
                'attribute' => 'post_id',
                'label' => '评论文章',
                'value' => 'post.title',
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {approve}',
                'buttons' => [
                    'approve' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', '审核'),
                            'aria-label' => Yii::t('yii', '审核'),
                            'data-confirm' => Yii::t('yii', '你确定通过这条评论吗？'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];

                        return Html::a('<span class="glyphicon glyphicon-check"></span>', $url, $options);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
