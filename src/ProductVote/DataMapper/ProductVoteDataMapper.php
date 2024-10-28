<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\DataMapper;

use OxidEsales\ModuleTemplate\ProductVote\DataType\ProductVote;
use OxidEsales\ModuleTemplate\ProductVote\Exception\MapDataTypeException;

readonly class ProductVoteDataMapper implements ProductVoteDataMapperInterface
{
    public function map(array $data): ProductVote
    {
        if (!isset($data['ProductId']) || !isset($data['UserId']) || !isset($data['Vote'])) {
            throw new MapDataTypeException();
        }

        return new ProductVote($data['ProductId'], $data['UserId'], (bool)$data['Vote']);
    }
}
