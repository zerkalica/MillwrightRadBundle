<?php
namespace Millwright\RadBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\DataTransformer\IntegerToLocalizedStringTransformer;

/**
 * Transforms between an integer (seconds conut) and an integer (days count).
 */
class SecondsToDaysTransformer extends IntegerToLocalizedStringTransformer
{
    const SECONDS_IN_DAY = 86400;

    /**
     * Transforms seconds (int) type into days count (int).
     *
     * @param integer $value count value.
     *
     * @return integer Days count value.
     *
     * @throws UnexpectedTypeException if the given value is not an integer
     * @throws TransformationFailedException if the value can not be transformed
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        //$value = parent::transform($value);

        // TODO: Maybe we need to use more complex logic
        $value = floor($value / self::SECONDS_IN_DAY);

        return $value;
    }

    /**
     * Transforms days count (int) type into seconds (int).
     *
     * @param integer $value Days count value.
     *
     * @return integer Seconds count value.
     *
     * @throws UnexpectedTypeException if the given value is not an integer
     * @throws TransformationFailedException if the value can not be transformed
     */
    public function reverseTransform($value)
    {
        if ('' === $value) {
            return null;
        }

        if ('NaN' === $value) {
            throw new TransformationFailedException('"NaN" is not a valid integer');
        }

        // TODO: How to validate owerflow values?
        //$value = parent::reverseTransform($value);

        $value = $value * self::SECONDS_IN_DAY;

        return $value;
    }
}
