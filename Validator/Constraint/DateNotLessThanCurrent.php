<?php
namespace Millwright\RadBundle\Validator\Constraint;

/**
 * Is Date not less than current
 *
 * @Annotation
 */
class DateNotLessThanCurrent extends Constraint
{
    protected $message = 'validator.date_less_than_current';
}
