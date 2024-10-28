<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\DataType;

readonly class ProductVote
{
    public function __construct(
        public string $productId,
        public string $userId,
        public bool $vote,
    ) {
    }
}
