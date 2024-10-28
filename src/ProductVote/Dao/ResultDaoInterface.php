<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\Dao;

use OxidEsales\ModuleTemplate\ProductVote\DataType\Result;

interface ResultDaoInterface
{
    public function getProductVoteResult(string $productId): array|Result;
}
