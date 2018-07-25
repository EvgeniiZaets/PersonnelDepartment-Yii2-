<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Vacation;

/* @var $this yii\web\View */
/* @var $searchModel app\forms\search\VacationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vacations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Vacation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_id',
            [
                'attribute' => 'employee_id',
                'value' => function (Vacation $vacation) {
                    return $vacation->employee->fullName;
                },
            ],
            'date_from',
            'date_to',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
