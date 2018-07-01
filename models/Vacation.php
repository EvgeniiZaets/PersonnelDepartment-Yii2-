<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%vacation}}".
 *
 * @property int $id
 * @property int $order_id
 * @property int $employee_id
 * @property string $date_from
 * @property string $date_to
 *
 * @property Employee $employee
 * @property Order $order
 */
class Vacation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%vacation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'employee_id', 'date_from', 'date_to'], 'required'],
            [['order_id', 'employee_id'], 'integer'],
            [['date_from', 'date_to'], 'safe'],
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
            'date_from' => 'Date From',
            'date_to' => 'Date To',
        ];
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
