<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\Service;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ModuleTemplate\ProductVote\Dao\ProductVoteDaoInterface;
use OxidEsales\ModuleTemplate\ProductVote\Dao\VoteResultDaoInterface;
use OxidEsales\ModuleTemplate\ProductVote\DataObject\ProductVote;
use OxidEsales\ModuleTemplate\ProductVote\DataObject\ProductVoteInterface;
use OxidEsales\ModuleTemplate\ProductVote\DataObject\VoteResultInterface;

readonly class VoteService implements VoteServiceInterface
{
    public function __construct(
        private ProductVoteDaoInterface $productVoteDao,
        private VoteResultDaoInterface $voteResultDao,
    ) {
    }

    public function getProductVote(Article $product, User $user): ?ProductVoteInterface
    {
        return $this->productVoteDao->getProductVote($product->getId(), $user->getId());
    }

    public function setProductVote(Article $product, User $user, bool $vote): void
    {
        $vote = new ProductVote($product->getId(), $user->getId(), $vote);
        $this->productVoteDao->setProductVote($vote);
    }

    public function resetProductVote(Article $product, User $user): void
    {
        $this->productVoteDao->resetProductVote($product->getId(), $user->getId());
    }

    public function getProductVoteResult(Article $product): VoteResultInterface
    {
        return $this->voteResultDao->getProductVoteResult($product->getId());
    }
}
