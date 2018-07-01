<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Vacation;

/* @var $this yii\web\View */
/* @var $model app\models\Vacation */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vacations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacation-view">

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
            'order_id',
            [
                'attribute' => 'employee_id',
                'value' => function (Vacation $vacation) {
                    return $vacation->employee->fullName;
                },
            ],
            'date_from',
            'date_to',
        ],
    ]) ?>

</div>
