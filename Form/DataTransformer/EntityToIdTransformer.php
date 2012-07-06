<?php
namespace Millwright\RadBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Transforms between a UserInterface instance and a username string.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class EntityToIdTransformer implements EntityToIdTransformerInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class = null;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
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

        if (!$this->class) {
            throw new \Exception('Property $class must be initialize.');
        }

        return $this->objectManager->find($this->class, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function setClass($class)
    {
        $this->class = $class;
    }
}
