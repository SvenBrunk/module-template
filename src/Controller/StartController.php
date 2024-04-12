<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Controller;

use OxidEsales\Eshop\Application\Model\User as EshopModelUser;
use OxidEsales\Eshop\Core\Registry as EshopRegistry;
use OxidEsales\ModuleTemplate\Service\GreetingMessageServiceInterface;
use OxidEsales\ModuleTemplate\Service\ModuleSettingsServiceInterface;

/**
 * @eshopExtension
 *
 * This is an example for a module extension (chain extend) of
 * the shop start controller.
 * NOTE: class must not be final.
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\StartController
 */
class StartController extends StartController_parent
{
    /**
     * All we need here is to fetch the information we need from a service.
     * As in our example we extend a block of a template belonging ONLY
     * to the shop's StartController, we extend that Controller with a new method.
     * NOTE: only leaf classes can be extended this way. The FrontendController class which
     *      many Controllers inherit from cannot be extended this way.
     */
    public function getOemtGreeting(): string
    {
        $service = $this->getService(GreetingMessageServiceInterface::class);

        $user   = is_a($this->getUser(), EshopModelUser::class) ? $this->getUser() : null;
        $result = $service->getGreeting($user);

        $result = EshopRegistry::getLang()->translateString($result);
        //Language::translateString() returns either array or string, so we need to handle this
        return is_array($result) ? (string) array_pop($result) : $result;
    }

    public function canUpdateOemtGreeting(): bool
    {
        $moduleSettings = $this->getService(ModuleSettingsServiceInterface::class);

        return is_a($this->getUser(), EshopModelUser::class) && $moduleSettings->isPersonalGreetingMode();
    }
}
