<?php

namespace app\repositories;

use app\models\Recruit;

interface RecruitRepositoryInterface
{
    /**
     * @param $id
     * @return Recruit
     */
    public function find($id);

    public function add(Recruit $Recruit);

    public function save(Recruit $Recruit);

    public function delete(Recruit $Recruit);
}