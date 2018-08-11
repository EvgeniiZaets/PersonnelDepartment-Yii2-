<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Interview;
use app\helpers\InterviewHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\forms\search\InterviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Interviews';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Join to Interview', ['join'], ['class' => 'btn btn-success']) ?>
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
            [
                'attribute' => 'status',
                'value' => function (Interview $interview) {
                    return InterviewHelper::getStatusName($interview->status);
                }
            ],
//            'reject_reason:ntext',
            'employee_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
