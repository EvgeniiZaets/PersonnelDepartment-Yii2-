<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\forms\search\BonusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bonuses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonus-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Bonus', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_id',
            'employee_id',
            'cost',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
