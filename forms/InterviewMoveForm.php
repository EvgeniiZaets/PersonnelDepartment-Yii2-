<?php

namespace app\forms;

use app\models\Interview;
use yii\base\Model;

class InterviewMoveForm extends Model
{
    public $date;

    private $interview;

    public function __construct(Interview $interview, $config = [])
    {
        $this->interview = $interview;
        $this->date = $interview->date;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['date'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'date' => 'New Date',
        ];
    }
}