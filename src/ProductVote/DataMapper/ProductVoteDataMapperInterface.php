<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\DataMapper;

use OxidEsales\ModuleTemplate\ProductVote\DataType\ProductVoteInterface;

interface ProductVoteDataMapperInterface
{
    public function mapFromDbRow(array $data): ProductVoteInterface;
}
