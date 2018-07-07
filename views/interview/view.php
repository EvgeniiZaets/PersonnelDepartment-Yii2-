<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Interview;
use app\helpers\InterviewHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Interview */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Interviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-view">

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
            'reject_reason:ntext',
            'employee_id',
        ],
    ]) ?>

</div>
