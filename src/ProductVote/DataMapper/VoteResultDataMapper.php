<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\DataMapper;

use OxidEsales\ModuleTemplate\ProductVote\DataObject\VoteResult;
use OxidEsales\ModuleTemplate\ProductVote\DataObject\VoteResultInterface;
use OxidEsales\ModuleTemplate\ProductVote\Exception\MapDataTypeException;

readonly class VoteResultDataMapper implements VoteResultDataMapperInterface
{
    public function mapFromDbRow(array $data): VoteResultInterface
    {
        if (!isset($data['ProductId']) || !isset($data['VoteUp']) || !isset($data['VoteDown'])) {
            throw new MapDataTypeException();
        }

        return new VoteResult($data['ProductId'], (int)$data['VoteUp'], (int)$data['VoteDown']);
    }
}
