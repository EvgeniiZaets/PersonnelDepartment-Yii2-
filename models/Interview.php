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
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%interview}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'first_name', 'last_name', 'status'], 'required'],
            [['date'], 'safe'],
            [['status', 'employee_id'], 'integer'],
            [['reject_reason'], 'string'],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 255],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
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
