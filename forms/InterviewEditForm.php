<?php

namespace app\forms;

use app\models\Interview;
use yii\base\Model;

class InterviewEditForm extends Model
{
    public $firstName;
    public $lastName;
    public $email;

    private $interview;

    public function __construct(Interview $interview, $config = [])
    {
        $this->interview = $interview;
        $this->firstName = $interview->first_name;
        $this->lastName = $interview->last_name;
        $this->email = $interview->email;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['firstName', 'lastName'], 'required'],
            [['email'], 'email'],
            [['firstName', 'lastName', 'email'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email'
        ];
    }


}