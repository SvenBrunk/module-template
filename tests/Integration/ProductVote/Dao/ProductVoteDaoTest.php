<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Tests\Integration\ProductVote\Dao;

use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ModuleTemplate\ProductVote\Dao\ProductVoteDao;
use OxidEsales\ModuleTemplate\ProductVote\Dao\ProductVoteDaoInterface;
use OxidEsales\ModuleTemplate\ProductVote\DataType\ProductVote;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(ProductVoteDao::class)]
final class ProductVoteDaoTest extends IntegrationTestCase
{
    use DaoTestTrait;

    #[Test]
    public function noVoteForNoRecord(): void
    {
        $sut = $this->get(ProductVoteDaoInterface::class);

        $productVote = $sut->getProductVote('productId', 'userId');
        $this->assertNull($productVote);
    }

    #[Test]
    #[DataProvider('boolProvider')]
    public function getUpVote(bool $value): void
    {
        $isVoteUp = $value;
        $this->executeInsertVoteQuery($isVoteUp);

        $sut = $this->get(ProductVoteDaoInterface::class);

        $vote = $sut->getProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID);
        $this->assertEquals(new ProductVote(
            self::TEST_PRODUCT_ID,
            self::TEST_USER_ID,
            $isVoteUp
        ), $vote);
    }

    #[Test]
    #[DataProvider('boolProvider')]
    public function setVote(bool $value): void
    {
        $isUpVote = $value;
        $vote = new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, $isUpVote);

        $sut = $this->get(ProductVoteDaoInterface::class);
        $sut->setProductVote($vote);

        $result = $this->getVoteQueryResult();
        $this->assertEquals(1, $result->rowCount());
        $this->assertEquals(
            [
                'oxartid' => self::TEST_PRODUCT_ID,
                'oxuserid' => self::TEST_USER_ID,
                'oxvote' => $isUpVote ? 1 : 0,
            ],
            $result->fetchAssociative()
        );
    }

    public static function boolProvider(): array
    {
        return [
            ['value' => true],
            ['value' => false],
        ];
    }

    #[Test]
    public function replaceVote(): void
    {
        $upVote = new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, true);
        $downVote = new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, false);

        $sut = $this->get(ProductVoteDaoInterface::class);
        $sut->setProductVote($upVote);
        $sut->setProductVote($downVote);

        $result = $this->getVoteQueryResult();
        $this->assertEquals(1, $result->rowCount());
        $this->assertEquals(
            [
                'oxartid' => self::TEST_PRODUCT_ID,
                'oxuserid' => self::TEST_USER_ID,
                'oxvote' => 0,
            ],
            $result->fetchAssociative()
        );
    }

    #[Test]
    public function resetNonExistingVote(): void
    {
        $sut = $this->get(ProductVoteDaoInterface::class);
        $vote = new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, true);
        $sut->resetProductVote($vote);

        $result = $this->getVoteQueryResult();
        $this->assertEquals(0, $result->rowCount());
    }

    #[Test]
    public function resetVote(): void
    {
        $this->executeInsertVoteQuery(true);

        $sut = $this->get(ProductVoteDaoInterface::class);
        $vote = new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, true);
        $sut->resetProductVote($vote);

        $result = $this->getVoteQueryResult();
        $this->assertEquals(0, $result->rowCount());
    }
}
