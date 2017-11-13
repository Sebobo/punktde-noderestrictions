<?php
namespace PunktDe\NodeRestrictions\Service;

/*
 * This file is part of the PunktDe.NodeRestrictions package.
 *
 * (c) punkt.de 2017
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Neos\Neos\Domain\Model\User;
use Neos\Neos\Service\UserService as NeosUserService;

/**
 * A service for managing users
 *
 * @Flow\Scope("singleton")
 */
class UserService extends NeosUserService
{
    /**
     * @return null
     */
    public function getFirstDynamicRole()
    {
        /** @var User $user */
        $user = $this->getBackendUser();

        if ($user === null) {
            return null;
        }

        /** @var Account $primaryAccount */
        $primaryAccount = $user->getAccounts()->first();
        $roles = array_keys($primaryAccount->getRoles());

//        \Neos\Flow\var_dump($roles);

        return count($roles) > 0 ? $roles[0] : null;
    }

}
