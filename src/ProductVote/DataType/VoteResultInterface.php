<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\DataType;

interface VoteResultInterface
{
    public function getProductId(): string;
    public function getVoteUp(): int;
    public function getVoteDown(): int;
}
