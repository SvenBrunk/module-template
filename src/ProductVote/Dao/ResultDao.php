<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\Dao;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\ModuleTemplate\ProductVote\DataMapper\ResultDataMapperInterface;
use OxidEsales\ModuleTemplate\ProductVote\DataType\Result;
use RuntimeException;

readonly class ResultDao implements ResultDaoInterface
{
    public function __construct(
        private QueryBuilderFactoryInterface $queryBuilderFactory,
        private ResultDataMapperInterface $dataMapper,
    ) {
    }

    public function getProductVoteResult(string $productId): Result
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->select([
                'oxartid as ProductId',
                'SUM(oxvote != 0) as VoteUp',
                'SUM(oxvote = 0) as VoteDown',
            ])
            ->from('oemt_product_vote')
            ->where('oxartid = :productId')
            ->groupBy('oxartid')
            ->setParameters([
                'productId' => $productId,
            ]);

        $queryResult = $queryBuilder->execute();
        if (!($queryResult instanceof \Doctrine\DBAL\Result)) {
            throw new RuntimeException('Query returned error.');
        }

        $row = $queryResult->fetchAssociative();

        if (!$row) {
            return new Result($productId, 0, 0);
        }
        return $this->dataMapper->map($row);
    }
}
