<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%interview}}".
 *
 * @property int $id
 * @property string $date
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property int $status
 * @property string $reject_reason
 * @property int $employee_id
 *
 * @property Employee $employee
 */
class Interview extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    const STATUS_NEW = 1;
    const STATUS_PASS = 2;
    const STATUS_REJECT = 3;

    public function getNextStatusList()
    {
        if ($this->status == self::STATUS_PASS) {
            return [
                self::STATUS_PASS => 'Passed'
            ];
        } elseif ($this->status == self::STATUS_REJECT) {
            return [
                self::STATUS_PASS => 'Passed',
                self::STATUS_REJECT => 'Rejected'
            ];
        } else {
            return [
                self::STATUS_NEW => 'New',
                self::STATUS_REJECT => 'Rejected',
                self::STATUS_PASS => 'Passed'
            ];
        }
    }

    /**
     * @param $insert - true когда insert, false когда update.
     * @param $changedAttributes - значения старых атрибутов которые изменились.
     */
    public function afterSave($insert, $changedAttributes)
    {
        // если статус изменился
        if (in_array('status', array_keys($changedAttributes)) && $this->status != $changedAttributes['status']) {
            if ($this->status == self::STATUS_NEW) {

            } elseif ($this->status == self::STATUS_PASS) {
                if ($this->email) {
                    Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($this->email)
                        ->setSubject('You are passed an interview!')
                        ->send();
                }
                $log = new Log();
                $log->message = $this->last_name . ' ' . $this->first_name . ' is passed an interview';
                $log->save();
            } elseif ($this->status == self::STATUS_REJECT) {
                if ($this->email) {
                    Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($this->email)
                        ->setSubject('You are failed an interview')
                        ->send();
                }
                $log = new Log();
                $log->message = $this->last_name . ' ' . $this->first_name . ' is failed an interview';
                $log->save();
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%interview}}';
    }

    /**
     * {@inheritdoc}
     * TODO::эти правила нам не пригодятся т.к. есть отдельная модель для валидации формы. (InterviewJoinForm)
     */
    public function rules()
    {
        return [
            [['date', 'first_name', 'last_name'], 'required'],
            [['status'], 'required', 'except' => self::SCENARIO_CREATE],
            [['status'], 'default', 'value' => self::STATUS_NEW], // если status не был передан из формаы, ставим STATUS_NEW.
            [['date'], 'safe'],
            // Условие на сервере и на клиенте, при котором обязательно для заполнения reject_reason.
            [['reject_reason'], 'required', 'when' => function (self $model) {
                    return $model->status == self::STATUS_REJECT;
                }, 'whenClient' => "function (attribute, value) {
                    return $('#interview-status').val() == '" . self::STATUS_REJECT . "';
                }"
            ],
            [['status', 'employee_id'], 'integer', 'except' => self::SCENARIO_CREATE],
            [['reject_reason'], 'string'],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'status' => 'Status',
            'reject_reason' => 'Reject Reason',
            'employee_id' => 'Employee',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }
}
