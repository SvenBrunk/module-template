<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Tests\Integration\ProductVote\Dao;

use Doctrine\DBAL\Result;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

trait DaoTestTrait
{
    protected const TEST_USER_ID = '_testuser';
    protected const TEST_PRODUCT_ID = '_testproduct';

    protected function getVoteQueryResult(): Result
    {
        $queryBuilder = $this->get(QueryBuilderFactoryInterface::class)->create();
        $queryBuilder
            ->select(['oxartid', 'oxuserid', 'oxvote'])
            ->from('oemt_product_vote')
            ->where('oxartid = :productId')
            ->andWhere('oxuserid = :userId')
            ->setParameters([
                'productId' => self::TEST_PRODUCT_ID,
                'userId' => self::TEST_USER_ID,
            ]);

        $result = $queryBuilder->execute();
        $this->assertInstanceOf(Result::class, $result);
        return $result;
    }

    protected function executeInsertVoteQuery(bool $isVoteUp, string $userId = self::TEST_USER_ID): void
    {
        $queryBuilder = $this->get(QueryBuilderFactoryInterface::class)->create();
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
                'productId' => self::TEST_PRODUCT_ID,
                'userId' => $userId,
                'vote' => (int)$isVoteUp
            ])
            ->execute();
    }
}
