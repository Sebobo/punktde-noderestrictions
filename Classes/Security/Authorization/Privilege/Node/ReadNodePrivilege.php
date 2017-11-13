<?php
namespace PunktDe\NodeRestrictions\Security\Authorization\Privilege\Node;

/*
 * This file is part of the PunktDe.NodeRestrictions package.
 *
 * (c) punkt.de 2017
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use PunktDe\NodeRestrictions\Security\Authorization\Privilege\Node\Doctrine\ConditionGenerator;
use Neos\ContentRepository\Security\Authorization\Privilege\Node\ReadNodePrivilege as NeosContentRepositoryReadNodePrivilege;

/**
 * {@inheritdoc}
 */
class ReadNodePrivilege extends NeosContentRepositoryReadNodePrivilege
{
    /**
     * {@inheritdoc}
     *
     * @return ConditionGenerator
     */
    protected function getConditionGenerator()
    {
        return new ConditionGenerator();
    }
}
