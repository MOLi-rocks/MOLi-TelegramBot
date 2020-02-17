<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\WhoUseWhatCommand;

class WhoUseWhatCommandRepository
{
    private $whoUseWhatCommandModel;

    /**
     * WhoUseWhatCommandRepository constructor.
     * @param WhoUseWhatCommand $whoUseWhatCommandModel
     */
    public function __construct(WhoUseWhatCommand $whoUseWhatCommandModel)
    {
        $this->whoUseWhatCommandModel = $whoUseWhatCommandModel;
    }

    public function getCommand($userId)
    {
        return $this->whoUseWhatCommandModel
            ->where('user-id', '=', $userId)
            ->first();
    }

    public function deleteCommands($userId)
    {
        return $this->whoUseWhatCommandModel
            ->where('user-id', '=', $userId)
            ->delete();

    }
}