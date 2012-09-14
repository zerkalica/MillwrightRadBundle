<?php
namespace Millwright\RadBundle\Validator\Constraint;

/**
 * Is valid phone number
 *
 * @Annotation
 */
class Phone extends Constraint
{
    protected $message = 'validator.not_valid_phone';
}
