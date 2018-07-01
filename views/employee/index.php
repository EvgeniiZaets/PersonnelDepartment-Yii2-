<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\EmployeeHelper;
use app\models\Employee;

/* @var $this yii\web\View */
/* @var $searchModel app\forms\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Employee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'first_name',
            'last_name',
            'address',
            'email:email',
            [
                'attribute' => 'status',
                'filter' => EmployeeHelper::getStatusList(), // в качестве фильтра - выпадающий список
                'value' => function (Employee $employee) { // в качестве значения - название статуса
                    return EmployeeHelper::getStatusName($employee->status);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
