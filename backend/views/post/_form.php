<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Poststatus;
use common\models\Adminuser;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tags')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList(ArrayHelper::map(Poststatus::find()->all(), 'id', 'name'), [
        'prompt' => '请设定文章状态',
    ]) ?>

    <?= $form->field($model, 'author_id')->dropDownList(ArrayHelper::map(Adminuser::find()->all(), 'id', 'nickname'), [
        'prompt' => '请选择作者',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
