<?php
namespace Millwright\RadBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transform phone to view phone format
 */
class PhoneToPhoneViewTransformer implements DataTransformerInterface
{
    protected $defaultCountryCode = '7';

    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        if (null !== $value) {
            if (strlen($value) == 11) {
                $countryCode = substr($value, 0, 1);
                $value       = substr($value, 1);
            } else {
                $countryCode = $this->defaultCountryCode;
            }
            $value = $countryCode . ' ' . substr($value, 0, 3) . ' ' .
                substr($value, 3, 3) . '-' .
                substr($value, 6, 2) . '-' .
                substr($value, 8);
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value)
    {
        $str = preg_replace('/[^\d]{0,}/', '', $value);
        if (strlen($str) == 10) {
           $str = $this->defaultCountryCode . $str;
        }

        return $str;
    }
}
