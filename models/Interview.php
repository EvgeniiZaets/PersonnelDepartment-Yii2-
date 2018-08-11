<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%interview}}".
 * ХРАНИТ ЛОГИКУ ПО ОБРАБОТКЕ ДЕЙСТВИЙ С САМИМ ИНТЕРВЬЮ.
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
    const STATUS_NEW = 1;
    const STATUS_PASS = 2;
    const STATUS_REJECT = 3;

    public static function join($lastName, $firstName, $email, $date)
    {
        $interview = new Interview();
        $interview->date = $date;
        $interview->last_name = $lastName;
        $interview->first_name = $firstName;
        $interview->email = $email;
        $interview->status = Interview::STATUS_NEW;
        return $interview;
    }

    public function editData($lastName, $firstName, $email)
    {
        $this->last_name = $lastName;
        $this->first_name = $firstName;
        $this->email = $email;
    }

    public function move($date)
    {
        $this->guardIsNew();
        $this->guardNotCurrentDate($date);
        $this->date = $date;
    }

    public function reject($reason)
    {
        $this->guardIsNotRejected();
        $this->reject_reason = $reason;
        $this->status = self::STATUS_REJECT;
    }

    public function remove()
    {
//        $this->guardIsNew();
//        $this->status = self::SATATUS_DELETED;
    }

    public function passBy(Employee $employee)
    {
        $this->guardIsNotPassed();
        $this->populateRelation('employee', $employee); // для заполнения employee_id
        $this->status = self::STATUS_PASS;
    }

    public function isNew()
    {
        return $this->status === Interview::STATUS_NEW;
    }

    public function isPassed()
    {
        return $this->status === Interview::STATUS_PASS;
    }

    public function isRejected()
    {
        return $this->status === Interview::STATUS_REJECT;
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

    private function guardIsNotRejected()
    {
        if ($this->isRejected())
            throw new \DomainException('Interview is already rejected');
    }

    private function guardIsNotPassed()
    {
        if ($this->isPassed())
            throw new \DomainException('Interview is already passed');
    }

    private function guardIsNew()
    {
        if (!$this->isNew())
            throw new \DomainException('Interview is new');
    }

    private function guardNotCurrentDate($date)
    {
        if ($date == $this->date)
            throw new \DomainException('Date is current.');
    }

    // Для сохранения связанных данных
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $related = $this->getRelatedRecords();
            /** @var Employee $employee */
            if (isset($related['employee']) && $employee = $related['employee']) {
                $employee->save();
                $this->employee_id = $employee->id;
            }
            return true;
        }
        return false;
    }
}
