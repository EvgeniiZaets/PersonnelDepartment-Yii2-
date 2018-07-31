<?php

namespace app\repositories;

use app\repositories\exceptions\NotFoundException;
use app\models\Position;

class PositionRepository implements PositionRepositoryInterface
{
    /**
     * @param $id
     * @return Position
     */
    public function find($id)
    {
        if (!$position = Position::findOne($id))
            throw new NotFoundException('Position not found');

        return $position;
    }

    public function add(Position $position)
    {
        if (!$position->getIsNewRecord())
            throw new \RuntimeException('Adding model.');

        if (!$position->insert(false))
            throw new \RuntimeException('Saving error.');
    }

    public function save(Position $position)
    {
        if ($position->getIsNewRecord())
            throw new \RuntimeException('Saving new model.');

        if ($position->update(false) === false)
            throw new \RuntimeException('Saving error.');
    }

    public function delete(Position $position)
    {
        if (!$position->delete())
            throw new \RuntimeException('Deleting error.');
    }
}