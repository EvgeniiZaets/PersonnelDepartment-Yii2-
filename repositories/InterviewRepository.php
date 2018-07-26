<?php

namespace app\repositories;

use app\forms\InterviewMoveForm;
use app\repositories\exceptions\NotFoundException;
use app\models\Interview;

class InterviewRepository
{
    /**
     * @param $id
     * @return Interview
     */
    public function find($id)
    {
        if (!$interview = Interview::findOne($id))
            throw new NotFoundException('Interview not found');

        return $interview;
    }

    public function add(Interview $interview)
    {
        if (!$interview->getIsNewRecord())
            throw new \RuntimeException('Adding model.');

        if (!$interview->insert(false))
            throw new \RuntimeException('Saving error.');
    }

    public function save(Interview $interview)
    {
        if ($interview->getIsNewRecord())
            throw new \RuntimeException('Saving new model.');

        if ($interview->update(false) === false)
            throw new \RuntimeException('Saving error.');
    }

    public function delete(Interview $interview)
    {
        if (!$interview->delete())
            throw new \RuntimeException('Deleting error.');
    }
}