<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\DataType;

readonly class Result
{
    public function __construct(
        public string $productId,
        public int $voteUp,
        public int $voteDown,
    ) {
    }
}
