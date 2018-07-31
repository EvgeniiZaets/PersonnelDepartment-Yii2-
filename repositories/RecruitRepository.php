<?php

namespace app\repositories;

use app\repositories\exceptions\NotFoundException;
use app\models\Recruit;

class RecruitRepository implements RecruitRepositoryInterface
{
    /**
     * @param $id
     * @return Recruit
     */
    public function find($id)
    {
        if (!$recruit = Recruit::findOne($id))
            throw new NotFoundException('Recruit not found');

        return $recruit;
    }

    public function add(Recruit $recruit)
    {
        if (!$recruit->getIsNewRecord())
            throw new \RuntimeException('Adding model.');

        if (!$recruit->insert(false))
            throw new \RuntimeException('Saving error.');
    }

    public function save(Recruit $recruit)
    {
        if ($recruit->getIsNewRecord())
            throw new \RuntimeException('Saving new model.');

        if ($recruit->update(false) === false)
            throw new \RuntimeException('Saving error.');
    }

    public function delete(Recruit $recruit)
    {
        if (!$recruit->delete())
            throw new \RuntimeException('Deleting error.');
    }
}