<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Order;
use app\models\Employee;
use app\models\Position;

/* @var $this yii\web\View */
/* @var $model app\models\Assignment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assignment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->dropDownList(ArrayHelper::map(Order::find()->asArray()->all(), 'id', 'id')) ?>

    <?= $form->field($model, 'employee_id')->dropDownList(ArrayHelper::map(Employee::find()->all(), 'id', 'fullName')) ?>

    <?= $form->field($model, 'position_id')->dropDownList(ArrayHelper::map(Position::find()->asArray()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'salary')->textInput() ?>

    <?= $form->field($model, 'active')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
