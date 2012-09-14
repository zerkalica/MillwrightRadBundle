<?php
namespace Millwright\RadBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Millwright\ConfigurationBundle\PhpUtil;
use Millwright\RadBundle\Validator\Constraint\Constraint as BaseConstraint;

/**
 * Validate dateFrom not less than current
 */
class Phone extends ConstraintValidator
{
    /**
     * @var string
     *
     * matches:
     * +7 (812)-223-23-23
     * +7 (812) 223-23-23
     * +7 812-223-23-23
     * +7 812 223 23 23
     */
    //protected $phoneRegex = '#^\(?\+?\d{1}[- ][2-9]\d{2}\)?[- ]\d{3}[- ]\d{2}[- ]\d{2}$#';

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var $constraint BaseConstraint */
        PhpUtil::assert($constraint instanceof BaseConstraint);

        if (strlen($value) != 11) {
            $this->context->addViolation($constraint->getMessage());
        }
    }
}
