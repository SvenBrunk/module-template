<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\DataMapper;

use OxidEsales\ModuleTemplate\ProductVote\DataObject\VoteResultInterface;

interface VoteResultDataMapperInterface
{
    /**
     * @param array<string,string> $data
     */
    public function mapFromDbRow(array $data): VoteResultInterface;
}
