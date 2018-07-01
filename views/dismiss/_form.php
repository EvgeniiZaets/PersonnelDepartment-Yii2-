<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Order;
use app\models\Employee;

/* @var $this yii\web\View */
/* @var $model app\models\Dismiss */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dismiss-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->dropDownList(ArrayHelper::map(Order::find()->asArray()->all(), 'id', 'id')) ?>

    <?= $form->field($model, 'employee_id')->dropDownList(ArrayHelper::map(Employee::find()->all(), 'id', 'fullName')) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'reason')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
