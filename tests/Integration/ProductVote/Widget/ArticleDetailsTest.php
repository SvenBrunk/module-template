<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Tests\Integration\ProductVote\Widget;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ModuleTemplate\ProductVote\Dao\ProductVoteDaoInterface;
use OxidEsales\ModuleTemplate\ProductVote\Dao\ResultDaoInterface;
use OxidEsales\ModuleTemplate\ProductVote\DataType\ProductVote;
use OxidEsales\ModuleTemplate\ProductVote\DataType\Result;
use OxidEsales\ModuleTemplate\ProductVote\Widget\ArticleDetails;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(ArticleDetails::class)]
final class ArticleDetailsTest extends TestCase
{
    private const TEST_PRODUCT_ID = 'test_product_id';
    private const TEST_USER_ID = 'test_user_id';

    #[Test]
    public function prepareDataNotLoggedIn(): void
    {
        $sut = $this->getSutMock();
        $sut
            ->method('getUser')
            ->willReturn(null);
        $sut
            ->method('getService')
            ->with(ResultDaoInterface::class)->willReturn($this->getResultDaoStub());

        $sut->prepareVoteData();

        $viewData = $sut->getViewData();
        $this->assertEquals($this->getResultDataType(), $viewData['productVoteResult']);
    }

    #[Test]
    public function prepareDataLoggedIn(): void
    {
        $sut = $this->getSutMock();
        $sut
            ->method('getUser')
            ->willReturn($this->getUserStub());
        $sut
            ->method('getService')
            ->willReturnMap([
                [ProductVoteDaoInterface::class, $this->getProductVoteDaoStub()],
                [ResultDaoInterface::class, $this->getResultDaoStub()],
            ]);

        $sut->prepareVoteData();

        $viewData = $sut->getViewData();
        $this->assertEquals($this->getResultDataType(), $viewData['productVoteResult']);
        $this->assertEquals($this->getProductVoteDataType(), $viewData['productVote']);
    }

    private function getSutMock(): ArticleDetails|MockObject
    {
        $sut = $this
            ->getMockBuilder(ArticleDetails::class)
            ->onlyMethods(['getProduct', 'getUser', 'getService'])
            ->getMock();
        $sut
            ->method('getProduct')
            ->willReturn($this->getProductStub());

        return $sut;
    }

    private function getProductStub(): Article|Stub
    {
        $productStub = $this->createStub(Article::class);
        $productStub
            ->method('getId')
            ->willReturn(self::TEST_PRODUCT_ID);

        return $productStub;
    }

    private function getUserStub(): User|Stub
    {
        $userStub = $this->createStub(User::class);
        $userStub
            ->method('getId')
            ->willReturn(self::TEST_USER_ID);

        return $userStub;
    }

    private function getProductVoteDaoStub(): ProductVoteDaoInterface|Stub
    {
        $stub = $this->createStub(ProductVoteDaoInterface::class);
        $stub
            ->method('getProductVote')
            ->willReturn($this->getProductVoteDataType());

        return $stub;
    }

    private function getProductVoteDataType(): ProductVote
    {
        return new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, true);
    }

    private function getResultDaoStub(): ResultDaoInterface|Stub
    {
        $stub = $this->createStub(ResultDaoInterface::class);
        $stub
            ->method('getProductVoteResult')
            ->willReturn($this->getResultDataType());

        return $stub;
    }

    private function getResultDataType(): Result
    {
        return new Result(self::TEST_PRODUCT_ID, 3, 2);
    }
}
