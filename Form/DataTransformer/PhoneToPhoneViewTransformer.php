<?php
namespace Millwright\RadBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transform phone to view phone format
 */
class PhoneToPhoneViewTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        if (null !== $value) {
            $value = substr($value, 0, 3) . ' ' .
                substr($value, 3, 3) . ' ' .
                substr($value, 6, 2) . ' ' .
                substr($value, 8);
        }

        // return xxx-xxx-xx-xx format
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value)
    {
        // return xxxxxxxxx format
        return preg_replace('/[^\d]{0,}/', '', $value);
    }
}
