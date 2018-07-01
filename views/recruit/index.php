<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Recruit;

/* @var $this yii\web\View */
/* @var $searchModel app\forms\RecruitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recruits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recruit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Recruit', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_id',
            'employee_id',
            [
                'attribute' => 'employee_id',
                'value' => function (Recruit $recruit) {
                    return $recruit->employee->fullName;
                },
            ],
            'date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
