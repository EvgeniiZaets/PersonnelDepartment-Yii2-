<?php

namespace app\repositories;

use app\models\Position;

interface PositionRepositoryInterface
{
    /**
     * @param $id
     * @return Position
     */
    public function find($id);

    public function add(Position $Position);

    public function save(Position $Position);

    public function delete(Position $Position);
}