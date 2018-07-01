<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Order;
use app\models\Employee;

/* @var $this yii\web\View */
/* @var $model app\models\Vacation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vacation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->dropDownList(ArrayHelper::map(Order::find()->all(), 'id', 'id')) ?>

    <?= $form->field($model, 'employee_id')->dropDownList(ArrayHelper::map(Employee::find()->all(), 'id', 'fullName')) ?>

    <?= $form->field($model, 'date_from')->textInput() ?>

    <?= $form->field($model, 'date_to')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
