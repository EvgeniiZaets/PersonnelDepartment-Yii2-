<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%employee}}".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property string $email
 * @property int $status
 *
 * @property Assignment[] $assignments
 * @property Bonus[] $bonuses
 * @property Dismiss[] $dismisses
 * @property Interview[] $interviews
 * @property Recruit[] $recruits
 * @property Vacation[] $vacations
 */
class Employee extends \yii\db\ActiveRecord
{
    const STATUS_PROBATION = 1;
    const STATUS_WORK = 2;
    const STATUS_VACATION = 3;
    const STATUS_DISMISS = 4;

    public function getFullName()
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%employee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'address', 'status'], 'required'],
            [['status'], 'integer'],
            [['first_name', 'last_name', 'address', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'address' => 'Address',
            'email' => 'Email',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignments()
    {
        return $this->hasMany(Assignment::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuses()
    {
        return $this->hasMany(Bonus::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDismisses()
    {
        return $this->hasMany(Dismiss::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInterviews()
    {
        return $this->hasMany(Interview::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecruits()
    {
        return $this->hasMany(Recruit::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVacations()
    {
        return $this->hasMany(Vacation::className(), ['employee_id' => 'id']);
    }
}
