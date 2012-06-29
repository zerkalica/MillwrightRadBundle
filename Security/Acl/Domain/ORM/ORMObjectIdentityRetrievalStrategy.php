<?php
namespace Millwright\RadBundle\Security\Acl\Domain\ORM;

use Doctrine\ORM\EntityManager;
use Millwright\RadBundle\Security\Acl\Domain\ObjectIdentityRetrievalStrategy;

use Symfony\Component\Validator\Mapping\ClassMetadataFactoryInterface;

/**
 * Strategy to be used for retrieving object identities from domain objects
 */
class ORMObjectIdentityRetrievalStrategy extends ObjectIdentityRetrievalStrategy
{
    protected $em;


    public function __construct(EntityManager $em, ClassMetadataFactoryInterface $factory)
    {
        $this->em = $em;
        $this->factory = $factory;
    }

    /**
     * {@inheritDoc}
     */
    protected function getNamespaces()
    {
        return $this->em->getConfiguration()->getEntityNamespaces();
    }
}
