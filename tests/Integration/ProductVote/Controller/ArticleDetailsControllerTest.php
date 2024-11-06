<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Tests\Integration\ProductVote\Controller;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ModuleTemplate\ProductVote\Controller\ArticleDetailsController;
use OxidEsales\ModuleTemplate\ProductVote\Dao\ProductVoteDaoInterface;
use OxidEsales\ModuleTemplate\ProductVote\DataType\ProductVote;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(ArticleDetailsController::class)]
final class ArticleDetailsControllerTest extends TestCase
{
    private const TEST_PRODUCT_ID = 'test_product_id';
    private const TEST_USER_ID = 'test_user_id';

    #[Test]
    public function voteNotLoggedIn(): void
    {
        $daoSpy = $this->createMock(ProductVoteDaoInterface::class);
        $daoSpy
            ->expects($this->never())
            ->method('setProductVote');
        $daoSpy
            ->expects($this->never())
            ->method('resetProductVote');

        $sut = $this->getSutMock($daoSpy, null, $this->getProductStub());

        $sut->voteUp();
        $sut->voteDown();
        $sut->resetVote();
    }

    #[Test]
    public function voteUp(): void
    {
        $daoSpy = $this->getDaoSpy(
            'setProductVote',
            new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, true)
        );
        $sut = $this->getSutMock($daoSpy, $this->getUserStub(), $this->getProductStub());

        $sut->voteUp();
    }

    #[Test]
    public function voteDown(): void
    {
        $daoSpy = $this->getDaoSpy(
            'setProductVote',
            new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, false)
        );
        $sut = $this->getSutMock($daoSpy, $this->getUserStub(), $this->getProductStub());

        $sut->voteDown();
    }

    #[Test]
    public function resetVote(): void
    {
        $daoSpy = $this->getDaoSpy('resetProductVote', self::TEST_PRODUCT_ID, self::TEST_USER_ID);
        $sut = $this->getSutMock($daoSpy, $this->getUserStub(), $this->getProductStub());

        $sut->resetVote();
    }

    private function getDaoSpy(string $method, mixed ...$arguments): ProductVoteDaoInterface|MockObject
    {
        $daoSpy = $this->createMock(ProductVoteDaoInterface::class);
        $daoSpy
            ->expects($this->once())
            ->method($method)
            ->with(...$arguments);

        return $daoSpy;
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

    private function getSutMock(
        ProductVoteDaoInterface|MockObject $daoSpy,
        Stub|User|null $userStub,
        Article|Stub $productStub,
    ): ArticleDetailsController|MockObject {
        $sut = $this
            ->getMockBuilder(ArticleDetailsController::class)
            ->onlyMethods(['getService', 'getProduct', 'getUser'])
            ->getMock();
        $sut
            ->method('getService')
            ->with(ProductVoteDaoInterface::class)->willReturn($daoSpy);
        $sut
            ->method('getUser')
            ->willReturn($userStub);
        $sut
            ->method('getProduct')
            ->willReturn($productStub);

        return $sut;
    }
}
