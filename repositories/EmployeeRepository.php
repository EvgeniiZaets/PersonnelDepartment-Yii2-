<?php

namespace app\repositories;

use app\repositories\exceptions\NotFoundException;
use app\models\Employee;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    /**
     * @param $id
     * @return Employee
     */
    public function find($id)
    {
        if (!$employee = Employee::findOne($id))
            throw new NotFoundException('Employee not found');

        return $employee;
    }

    public function add(Employee $employee)
    {
        if (!$employee->getIsNewRecord())
            throw new \RuntimeException('Adding model.');

        if (!$employee->insert(false))
            throw new \RuntimeException('Saving error.');
    }

    public function save(Employee $employee)
    {
        if ($employee->getIsNewRecord())
            throw new \RuntimeException('Saving new model.');

        if ($employee->update(false) === false)
            throw new \RuntimeException('Saving error.');
    }

    public function delete(Employee $employee)
    {
        if (!$employee->delete())
            throw new \RuntimeException('Deleting error.');
    }
}