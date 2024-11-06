<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\ProductVote\Widget;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ModuleTemplate\ProductVote\Dao\ProductVoteDaoInterface;
use OxidEsales\ModuleTemplate\ProductVote\Dao\ResultDaoInterface;

/**
 * @extendable-class
 *
 * This is a brand new (module own) controller which extends from the
 * shop frontend controller class.
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class ArticleDetails extends ArticleDetails_parent
{
    public function render()
    {
        $this->prepareVoteData();
        return parent::render();
    }

    public function prepareVoteData(): void
    {
        /** @var Article $product */
        $product = $this->getProduct();
        /** @var User $user */
        $user = $this->getUser();

        if ($user instanceof User) {
            $productVoteDao = $this->getProductVoteDao();
            $this->_aViewData['productVote'] = $productVoteDao->getProductVote($product->getId(), $user->getId());
        }

        $resultDao = $this->getProductVoteResultDao();
        $this->_aViewData['productVoteResult'] = $resultDao->getProductVoteResult($product->getId());
    }

    private function getProductVoteDao(): ProductVoteDaoInterface
    {
        return $this->getService(ProductVoteDaoInterface::class);
    }

    private function getProductVoteResultDao(): ResultDaoInterface
    {
        return $this->getService(ResultDaoInterface::class);
    }
}
