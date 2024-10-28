<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\Dao;

use Doctrine\DBAL\Result;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\ModuleTemplate\ProductVote\DataMapper\ProductVoteDataMapperInterface;
use OxidEsales\ModuleTemplate\ProductVote\DataType\ProductVote;
use RuntimeException;

readonly class ProductVoteDao implements ProductVoteDaoInterface
{
    public function __construct(
        private QueryBuilderFactoryInterface $queryBuilderFactory,
        private ProductVoteDataMapperInterface $dataMapper,
    ) {
    }

    public function getProductVote(string $productId, string $userId): ?ProductVote
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->select([
                'oxartid as ProductId',
                'oxuserid as UserId',
                'oxvote as Vote',
            ])
            ->from('oemt_product_vote')
            ->where('oxartid = :productId')
            ->andWhere('oxuserid = :userId')
            ->setParameters([
                'productId' => $productId,
                'userId' => $userId,
            ]);

        $result = $queryBuilder->execute();
        if (!($result instanceof Result)) {
            throw new RuntimeException('Query returned error.');
        }

        $row = $result->fetchAssociative();
        if ($row === false) {
            return null;
        }

        return $this->dataMapper->map($row);
    }

    public function setProductVote(ProductVote $vote): void
    {
        $this->resetProductVote($vote);

        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->insert('oemt_product_vote')
            ->values([
                'oxid' => ':oxid',
                'oxartid' => ':productId',
                'oxuserid' => ':userId',
                'oxvote' => ':vote',
            ])
            ->setParameters([
                'oxid' => uniqid(),
                'productId' => $vote->productId,
                'userId' => $vote->userId,
                'vote' => (int)$vote->vote,
            ])
            ->execute();
    }

    public function resetProductVote(ProductVote $vote): void
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->delete('oemt_product_vote')
            ->where('oxartid = :productId')
            ->andWhere('oxuserid = :userId')
            ->setParameters([
                'productId' => $vote->productId,
                'userId'    => $vote->userId,
            ])
            ->execute();
    }
}
