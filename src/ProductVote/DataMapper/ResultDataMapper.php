<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\DataMapper;

use OxidEsales\ModuleTemplate\ProductVote\DataType\Result;
use OxidEsales\ModuleTemplate\ProductVote\Exception\MapDataTypeException;

readonly class ResultDataMapper implements ResultDataMapperInterface
{
    public function map(array $data): Result
    {
        if (!isset($data['ProductId']) || !isset($data['VoteUp']) || !isset($data['VoteDown'])) {
            throw new MapDataTypeException();
        }

        return new Result($data['ProductId'], (int)$data['VoteUp'], (int)$data['VoteDown']);
    }
}
