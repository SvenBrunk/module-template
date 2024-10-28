<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Tests\Integration\ProductVote\Dao;

use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ModuleTemplate\ProductVote\Dao\ResultDao;
use OxidEsales\ModuleTemplate\ProductVote\Dao\ResultDaoInterface;
use OxidEsales\ModuleTemplate\ProductVote\DataType\Result;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(ResultDao::class)]
final class ResultDaoTest extends IntegrationTestCase
{
    use DaoTestTrait;

    #[Test]
    public function calculateNoVotes(): void
    {
        $sut = $this->get(ResultDaoInterface::class);
        $result = $sut->getProductVoteResult(self::TEST_PRODUCT_ID);

        $this->assertEquals(new Result(self::TEST_PRODUCT_ID, 0, 0), $result);
    }

    #[Test]
    public function calculateVoteResult(): void
    {
        $this->executeInsertVoteQuery(true, 'user_1');

        $sut = $this->get(ResultDaoInterface::class);
        $result = $sut->getProductVoteResult(self::TEST_PRODUCT_ID);
        $this->assertEquals(new Result(self::TEST_PRODUCT_ID, 1, 0), $result);
    }

    #[Test]
    public function calculateVotesResult(): void
    {
        $this->executeInsertVoteQuery(true, 'user_1'); // 1/0
        $this->executeInsertVoteQuery(false, 'user_2');// 1/1
        $this->executeInsertVoteQuery(false, 'user_3');// 1/2
        $this->executeInsertVoteQuery(false, 'user_4');// 1/3
        $this->executeInsertVoteQuery(true, 'user_5'); // 2/3
        $this->executeInsertVoteQuery(true, 'user_6'); // 3/3
        $this->executeInsertVoteQuery(true, 'user_7'); // 4/3

        $sut = $this->get(ResultDaoInterface::class);
        $result = $sut->getProductVoteResult(self::TEST_PRODUCT_ID);
        $this->assertEquals(new Result(self::TEST_PRODUCT_ID, 4, 3), $result);
    }
}
