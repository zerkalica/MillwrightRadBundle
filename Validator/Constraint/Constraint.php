<?php
namespace Millwright\RadBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint as ConstraintBase;
use Doctrine\Common\Util\ClassUtils;

/**
 * Astract constraint
 *
 */
abstract class Constraint extends ConstraintBase
{
    /**
     * Validator message
     *
     * @var string
     */
    protected $message;

    /**
     * Validator service name, leave empty, if validator class not uses any services
     *
     * @var string|null
     */
    protected $service;

    /**
     * Validator target: self::PROPERTY_CONSTRAINT or self::CLASS_CONSTRAINT
     *
     * @var string
     */
    protected $targets = self::PROPERTY_CONSTRAINT;

    /**
     * Get message string for validator
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritDoc}
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * {@inheritDoc}
     */
    public function validatedBy()
    {
        if (!$this->service) {
            $class = ClassUtils::getClass($this);

            $parts = explode('\\', $class);

            $class = array_pop($parts);
            array_pop($parts);
            $ns = implode('\\', $parts);

            return $ns . '\\' . $class;
        } else {
            return $this->service;
        }
    }
}
