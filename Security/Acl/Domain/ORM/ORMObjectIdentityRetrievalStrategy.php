<?php
namespace Millwright\RadBundle\Security\Acl\Domain\ORM;

use Doctrine\ORM\EntityManager;
use Millwright\RadBundle\Security\Acl\Domain\ObjectIdentityRetrievalStrategy;

/**
 * Strategy to be used for retrieving object identities from domain objects
 */
class ORMObjectIdentityRetrievalStrategy extends ObjectIdentityRetrievalStrategy
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    protected function getNamespaces()
    {
        return $this->em->getConfiguration()->getEntityNamespaces();
    }
}
