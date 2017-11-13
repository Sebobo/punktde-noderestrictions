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
use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\ConjunctionGenerator;
use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\FalseConditionGenerator;
use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\NotExpressionGenerator;
use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\PropertyConditionGenerator;
use Neos\ContentRepository\Security\Authorization\Privilege\Node\Doctrine\ConditionGenerator as NeosContentRepositoryConditionGenerator;
use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\SqlGeneratorInterface;
use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\TrueConditionGenerator;

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

        $value = $propertyConditionGenerator->getValueForOperand($value);

        return $propertyConditionGenerator->like('%"' . trim($property) . '": ' . json_encode($value) . '%');
    }

    /**
     * @param string $property
     * @param mixed $value
     *
     * @return SqlGeneratorInterface
     */
    public function nodePropertyIn($property, $value)
    {
        $propertyConditionGenerator = new PropertyConditionGenerator('properties');

        if (!is_string($property) || is_array($value)) {
            return new FalseConditionGenerator();
        }

        $value = $propertyConditionGenerator->getValueForOperand($value);

        return $propertyConditionGenerator->in($value);
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

    /**
     * @param string $property
     * @param mixed $value
     *
     * @return SqlGeneratorInterface
     */
    public function parentNodePropertyIn($property, $value)
    {
        $propertiesConditionGenerator = $this->nodePropertyIn($property, $value);
        $subQueryGenerator = new ParentNodePropertyGenerator($propertiesConditionGenerator);

        return $subQueryGenerator;
    }

    /**
     * @param $property
     * @return SqlGeneratorInterface
     */
    public function hasNodeProperty($property)
    {
        $propertyConditionGenerator = new PropertyConditionGenerator('properties');

        if (!is_string($property)) {
            return new FalseConditionGenerator();
        }

        return $propertyConditionGenerator->like('%"' . trim($property) . '": "%');
    }

    /**
     * @param $property
     * @return SqlGeneratorInterface
     */
    public function hasEmptyProperty($property)
    {
        $propertyConditionGenerator = new PropertyConditionGenerator('properties');

        if (!is_string($property)) {
            return new FalseConditionGenerator();
        }

        return $propertyConditionGenerator->like('%"' . trim($property) . '": ""%');
    }
}
