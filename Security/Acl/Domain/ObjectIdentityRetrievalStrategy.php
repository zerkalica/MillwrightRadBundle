<?php
namespace Millwright\RadBundle\Security\Acl\Domain;

use Symfony\Component\Security\Acl\Exception\InvalidDomainObjectException;
use Symfony\Component\Security\Acl\Model\ObjectIdentityRetrievalStrategyInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

/**
 * Strategy to be used for retrieving object identities from domain objects
 */
abstract class ObjectIdentityRetrievalStrategy implements ObjectIdentityRetrievalStrategyInterface
{
    protected $namespaces;

    /**
     * {@inheritDoc}
     */
    public function getObjectIdentity($domainObject)
    {
        try {
            $oid   = ObjectIdentity::fromDomainObject($domainObject);
            $type  = $this->getAliasByClassName($oid->getType());

            return new ObjectIdentity($oid->getIdentifier(), $type);
        } catch (InvalidDomainObjectException $failed) {
            return null;
        }
    }

    /**
     * Get namespaces to aliases map
     *
     * @return array
     */
    abstract protected function getNamespaces();

    protected function getAliasByClassName($className)
    {
        if (!$this->namespaces) {
            $namespaces = $this->getNamespaces();
            $this->namespaces = array_flip($namespaces);
        }

        foreach($this->namespaces as $ns => $alias) {
            if (substr($className, 0, strlen($ns)) == $ns) {
                $className = $alias . ':' . substr($className, strlen($ns) + 1);
                break;
            }
        }

        return $className;
    }
}
