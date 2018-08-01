<?php

namespace app\repositories;

use app\repositories\exceptions\NotFoundException;
use app\models\Assignment;

class AssignmentRepository implements AssignmentRepositoryInterface
{
    /**
     * @param $id
     * @return Assignment
     */
    public function find($id)
    {
        if (!$assignment = Assignment::findOne($id))
            throw new NotFoundException('Assignment not found');

        return $assignment;
    }

    public function add(Assignment $assignment)
    {
        if (!$assignment->getIsNewRecord())
            throw new \RuntimeException('Adding model.');

        if (!$assignment->insert(false))
            throw new \RuntimeException('Saving error.');
    }

    public function save(Assignment $assignment)
    {
        if ($assignment->getIsNewRecord())
            throw new \RuntimeException('Saving new model.');

        if ($assignment->update(false) === false)
            throw new \RuntimeException('Saving error.');
    }

    public function delete(Assignment $assignment)
    {
        if (!$assignment->delete())
            throw new \RuntimeException('Deleting error.');
    }
}