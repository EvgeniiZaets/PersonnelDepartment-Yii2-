<?php

namespace app\repositories;

use app\repositories\exceptions\NotFoundException;
use app\models\Contract;

class ContractRepository implements ContractRepositoryInterface
{
    /**
     * @param $id
     * @return Contract
     */
    public function find($id)
    {
        if (!$contract = Contract::findOne($id))
            throw new NotFoundException('Contract not found');

        return $contract;
    }

    public function add(Contract $contract)
    {
        if (!$contract->getIsNewRecord())
            throw new \RuntimeException('Adding model.');

        if (!$contract->insert(false))
            throw new \RuntimeException('Saving error.');
    }

    public function save(Contract $contract)
    {
        if ($contract->getIsNewRecord())
            throw new \RuntimeException('Saving new model.');

        if ($contract->update(false) === false)
            throw new \RuntimeException('Saving error.');
    }

    public function delete(Contract $contract)
    {
        if (!$contract->delete())
            throw new \RuntimeException('Deleting error.');
    }
}