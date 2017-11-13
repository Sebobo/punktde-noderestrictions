<?php
namespace PunktDe\NodeRestrictions\Security\Policy;

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
use Neos\Flow\Security\Context;
use Neos\Neos\Domain\Service\UserService;
use Neos\Neos\Domain\Model\User;

/**
 * Dynamic role generator
 *
 * @Flow\Scope("singleton")
 */
class RoleGenerator
{
    /**
     * @Flow\Inject
     * @var UserService
     */
    protected $userService;


    /**
     * @Flow\Inject
     * @var Context
     */
    protected $context;

    /**
     * Adds a separate role for each user in the system
     *
     * @param array $policyConfiguration
     */
    public function generateDynamicRoles(array &$policyConfiguration)
    {
        $this->context->withoutAuthorizationChecks(function() use (&$users, &$policyConfiguration) {
            $users = $this->userService->getUsers()->toArray();

            /** @var User $user */
            foreach ($users as $user) {
                /** @var Account $primaryAccount */
                $primaryAccount = $user->getAccounts()->first();
                $accountIdentifier = $primaryAccount->getAccountIdentifier();
                $roleIdentifier = 'PunktDe.NodePrivileges:' . ucfirst($accountIdentifier);

                // Append account identifier as new role
                $policyConfiguration['roles'][$roleIdentifier] = [
                    'parentRoles' => [
                        'PunktDe.NodeRestrictions:DynamicRole'
                    ]
                ];
            }
        });
    }
}
