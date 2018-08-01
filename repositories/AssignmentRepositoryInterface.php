<?php

namespace app\repositories;

use app\models\Assignment;

interface AssignmentRepositoryInterface
{
    /**
     * @param $id
     * @return Assignment
     */
    public function find($id);

    public function add(Assignment $Assignment);

    public function save(Assignment $Assignment);

    public function delete(Assignment $Assignment);
}