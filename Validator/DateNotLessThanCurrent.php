<?php
namespace Millwright\RadBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Millwright\ConfigurationBundle\PhpUtil;
use Millwright\RadBundle\Validator\Constraint\Constraint as BaseConstraint;

/**
 * Validate dateFrom not less than current
 */
class DateNotLessThanCurrent extends ConstraintValidator
{
    /**
     * {@inheritDoc}
     */
    public function validate($date, Constraint $constraint)
    {
        /** @var $constraint BaseConstraint */
        /** @var $date \DateTime */
        PhpUtil::assert($constraint instanceof BaseConstraint);

        if ($date) {
            PhpUtil::assert($date instanceof \DateTime);
        }

        $currentDate = new \DateTime;

        if (null === $date || $date < $currentDate) {
            $this->context->addViolation($constraint->getMessage());
        }
    }
}
