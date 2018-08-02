<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%contract}}".
 *
 * @property int $id
 * @property int $employee_id
 * @property string $first_name
 * @property string $last_name
 * @property string $date_open
 * @property string $date_close
 * @property string $close_reason
 */
class Contract extends \yii\db\ActiveRecord
{
    public static function create(Employee $employee, $lastName, $firstName, $dateOpen)
    {
        $contract = new self;
        // P.S фреймворк не сохранит связанные данные присвоенные таким образом, это надо делать вручную
        // Вложение обьектов по связи друг в друга надо обработать (см. beforSave())
        $contract->populateRelation('employee', $employee); // Заполняет связь employee спомощью $employee.
        $contract->last_name = $lastName;
        $contract->first_name = $firstName;
        $contract->date_open = $dateOpen;
        return $contract;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contract}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id', 'first_name', 'last_name', 'date_open'], 'required'],
            [['employee_id'], 'integer'],
            [['date_open', 'date_close'], 'safe'],
            [['close_reason'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'date_open' => 'Date Open',
            'date_close' => 'Date Close',
            'close_reason' => 'Close Reason',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Получаем все связанные записи, которые у нас есть.
            $related = $this->getRelatedRecords();
            // Если там присвоен сотрудник
            /** @var Employee $employee */
            if (isset($related['employee']) && $employee = $related['employee']) {
                $employee->save();
                $this->employee_id = $employee->id; // присваеваем автоинкрементный айдишник после сохраниения.
            }
            return true;
        }
        return true;
    }
}
