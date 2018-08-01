<?php
namespace app\services;

class TransactionManager
{
    public function begin()
    {
        return new Transaction(\Yii::$app->db->beginTransaction());
    }
}