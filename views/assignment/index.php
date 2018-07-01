<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\forms\AssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Assignments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assignment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Assignment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_id',
            'employee_id',
            'position_id',
            'date',
            //'rate',
            //'salary',
            //'active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
