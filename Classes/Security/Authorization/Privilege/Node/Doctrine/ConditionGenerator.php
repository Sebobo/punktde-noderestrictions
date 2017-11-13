<?php

namespace PunktDe\NodeRestrictions\Security\Authorization\Privilege\Node\Doctrine;

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
use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\FalseConditionGenerator;
use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\PropertyConditionGenerator;
use Neos\ContentRepository\Security\Authorization\Privilege\Node\Doctrine\ConditionGenerator as NeosContentRepositoryConditionGenerator;
use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\SqlGeneratorInterface;

/**
 * {@inheritdoc}
 */
class ConditionGenerator extends NeosContentRepositoryConditionGenerator
{
    /**
     * @param string $property
     * @param mixed $value
     *
     * @return SqlGeneratorInterface
     */
    public function nodePropertyIs($property, $value)
    {
        $propertyConditionGenerator = new PropertyConditionGenerator('properties');

        if (!is_string($property) || is_array($value)) {
            return new FalseConditionGenerator();
        }

        return $propertyConditionGenerator->like('%"' . trim($property) . '": ' . json_encode($value) . '%');
    }

    /**
     * @param string $property
     * @param mixed $value
     *
     * @return SqlGeneratorInterface
     */
    public function parentNodePropertyIs($property, $value)
    {
        $propertiesConditionGenerator = $this->nodePropertyIs($property, $value);
        $subQueryGenerator = new ParentNodePropertyGenerator($propertiesConditionGenerator);

        return $subQueryGenerator;
    }
}
