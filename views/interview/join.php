<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $joinForm app\forms\InterviewJoinForm */

$this->title = 'Join to Interview';
$this->params['breadcrumbs'][] = ['label' => 'Interviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-error alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i>Saved!</h4>
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<div class="interview-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="interview-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($joinForm, 'date')->textInput() ?>

        <?= $form->field($joinForm, 'firstName')->textInput(['maxlength' => true]) ?>

        <?= $form->field($joinForm, 'lastName')->textInput(['maxlength' => true]) ?>

        <?= $form->field($joinForm, 'email')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Join', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
