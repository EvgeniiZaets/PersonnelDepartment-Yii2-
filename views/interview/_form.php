<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Employee;
use app\models\Interview;
use app\helpers\InterviewHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Interview */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interview-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php if ($model->getScenario() != Interview::SCENARIO_CREATE): ?>

        <?= $form->field($model, 'status')->dropDownList($model->getNextStatusList()) ?>

        <?= $form->field($model, 'reject_reason')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'employee_id')->dropDownList(
                ArrayHelper::map(Employee::find()->all(), 'id', 'fullName'),
                ['prompt' => 'Select employee']
            ) ?>

    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
