<?php

namespace app\repositories;

use app\models\Contract;

interface ContractRepositoryInterface
{
    /**
     * @param $id
     * @return Contract
     */
    public function find($id);

    public function add(Contract $Contract);

    public function save(Contract $Contract);

    public function delete(Contract $Contract);
}