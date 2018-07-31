<?php

namespace app\repositories;

use app\models\Employee;

interface EmployeeRepositoryInterface
{
    /**
     * @param $id
     * @return Employee
     */
    public function find($id);

    public function add(Employee $Employee);

    public function save(Employee $Employee);

    public function delete(Employee $Employee);
}