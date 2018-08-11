<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $createForm app\forms\EmployeeCreateForm */

$this->title = 'Create Employee';
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $createForm,
    ]) ?>

</div>
