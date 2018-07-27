<?php

namespace app\repositories;

use app\models\Interview;

interface InterviewRepositoryInterface
{
    /**
     * @param $id
     * @return Interview
     */
    public function find($id);

    public function add(Interview $interview);

    public function save(Interview $interview);

    public function delete(Interview $interview);
}