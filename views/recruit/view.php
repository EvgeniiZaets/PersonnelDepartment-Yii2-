<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Recruit;

/* @var $this yii\web\View */
/* @var $model app\models\Recruit */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Recruits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recruit-view">

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
                'value' => function (Recruit $recruit) {
                    return $recruit->employee->fullName;
                },
            ],
            'date',
        ],
    ]) ?>

</div>
