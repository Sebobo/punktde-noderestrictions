<?php
namespace PunktDe\NodeRestrictions;

/*
 * This file is part of the PunktDe.NodeRestrictions package.
 *
 * (c) punkt.de 2017
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Flow\Security\Policy\PolicyService;
use PunktDe\NodeRestrictions\Security\Policy\RoleGenerator;

class Package extends BasePackage
{

    /**
     * Invokes custom PHP code directly after the package manager has been initialized.
     *
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $dispatcher->connect(
            PolicyService::class, 'configurationLoaded',
            RoleGenerator::class, 'generateDynamicRoles'
        );
    }
}
