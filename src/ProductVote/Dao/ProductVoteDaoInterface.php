<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\Dao;

use OxidEsales\ModuleTemplate\ProductVote\DataObject\ProductVoteInterface;

interface ProductVoteDaoInterface
{
    public function getProductVote(string $productId, string $userId): ?ProductVoteInterface;

    public function setProductVote(ProductVoteInterface $vote): void;
    public function resetProductVote(string $productId, string $userId): void;
}
