<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%assignment}}".
 *
 * @property int $id
 * @property int $order_id
 * @property int $employee_id
 * @property int $position_id
 * @property string $date
 * @property string $rate
 * @property int $salary
 * @property int $active
 *
 * @property Position $position
 * @property Employee $employee
 * @property Order $order
 */
class Assignment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%assignment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'employee_id', 'position_id', 'date', 'rate', 'salary', 'active'], 'required'],
            [['order_id', 'employee_id', 'position_id', 'salary', 'active'], 'integer'],
            [['date'], 'safe'],
            [['rate'], 'number'],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'id']],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order',
            'employee_id' => 'Employee',
            'position_id' => 'Position ID',
            'date' => 'Date',
            'rate' => 'Rate',
            'salary' => 'Salary',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Position::className(), ['id' => 'position_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
