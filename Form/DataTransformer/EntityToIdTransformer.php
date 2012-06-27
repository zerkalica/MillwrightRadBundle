<?php

namespace Millwright\RadBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Transforms between a UserInterface instance and a username string.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class EntityToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @param string        $class
     * @param ObjectManager $objectManager
     */
    public function __construct($class, ObjectManager $objectManager)
    {
        $this->class         = $class;
        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritDoc}
     */
    public function transform($entity)
    {
        if (null === $entity) {
            return null;
        }

        return $entity->getId();
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($id)
    {
        if (null === $id || '' === $id) {
            return null;
        }

        return $this->objectManager->find($this->class, $id);
    }
}
