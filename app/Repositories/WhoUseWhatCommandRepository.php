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
}