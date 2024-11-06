<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\Controller;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ModuleTemplate\ProductVote\Dao\ProductVoteDaoInterface;
use OxidEsales\ModuleTemplate\ProductVote\DataType\ProductVote;

/**
 * @extendable-class
 *
 * This is a brand new (module own) controller which extends from the
 * shop frontend controller class.
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class ArticleDetailsController extends ArticleDetailsController_parent
{
    public function voteUp(): void
    {
        $this->vote(true);
    }

    public function voteDown(): void
    {
        $this->vote(false);
    }

    public function resetVote(): void
    {
        $userId = $this->getUserId();
        if (!$userId) {
            return;
        }

        $productVoteDao = $this->getProductVoteDao();
        $productVoteDao->resetProductVote($this->getProduct()->getId(), $userId);
    }

    private function vote(bool $isUp): void
    {
        $userId = $this->getUserId();
        if (!$userId) {
            return;
        }

        $productVoteDao = $this->getProductVoteDao();
        $vote = new ProductVote($this->getProduct()->getId(), $userId, $isUp);
        $productVoteDao->setProductVote($vote);
    }

    private function getUserId(): ?string
    {
        $user = $this->getUser();
        if (!($user instanceof User)) {
            return null;
        }

        return $user->getId();
    }

    private function getProductVoteDao(): ProductVoteDaoInterface
    {
        return $this->getService(ProductVoteDaoInterface::class);
    }
}
