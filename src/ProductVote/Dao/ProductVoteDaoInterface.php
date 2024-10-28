<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\Dao;

use OxidEsales\ModuleTemplate\ProductVote\DataType\ProductVote;

interface ProductVoteDaoInterface
{
    public function getProductVote(string $productId, string $userId): ?ProductVote;

    public function setProductVote(ProductVote $vote): void;
    public function resetProductVote(ProductVote $vote): void;
}
