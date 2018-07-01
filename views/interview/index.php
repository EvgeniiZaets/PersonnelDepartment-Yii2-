<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\forms\InterviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Interviews';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Interview', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date',
            'first_name',
            'last_name',
            'email:email',
            'status',
            'reject_reason:ntext',
            'employee_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
