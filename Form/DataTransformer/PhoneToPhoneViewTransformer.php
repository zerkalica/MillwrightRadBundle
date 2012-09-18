<?php
namespace Millwright\RadBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transform phone to view phone format
 */
class PhoneToPhoneViewTransformer implements DataTransformerInterface
{
    protected $defaultCountryCode = '7';
    protected $defaultSeparator   = '-';

    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        $newValue = '';

        if (null !== $value) {
            $len = strlen($value);

            for($i = 0; $i < $len; $i++) {
                // Add placeholders to fromat phone like x xxx xxx-xx-xx
                if ($i == 4 || $i == 1) {
                    $newValue .= ' ';
                } elseif ($i== 7 || $i == 9) {
                    $newValue .= $this->defaultSeparator;
                }

                $newValue .= $value[$i];
            }
        }

        return $newValue;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value)
    {
        $str = preg_replace('/[^\d]{0,}/', '', $value);

        return $str;
    }
}
