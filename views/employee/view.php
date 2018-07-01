<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\EmployeeHelper;
use app\models\Employee;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'first_name',
            'last_name',
            'address',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function (Employee $employee) { // в качестве значения - название статуса
                    return EmployeeHelper::getStatusName($employee->status);
                }
            ],
        ],
    ]) ?>

</div>
