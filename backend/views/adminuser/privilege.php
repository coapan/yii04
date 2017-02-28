<?php
/**
 * Created by PhpStorm.
 * User: PanChaoZhi
 * Date: 2017/2/28
 * Time: 23:12
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Adminuser;

$model = Adminuser::findOne($id);
$this->title = '权限管理: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => '管理员', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="adminuser-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="adminuser-privilege-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= Html::checkboxList('privilege', $AuthAssignmentArray, $allPrivilegesArray) ?>

        <div class="form-group">
            <?= Html::submitButton('设置') ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>