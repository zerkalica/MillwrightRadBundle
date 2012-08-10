<?php
namespace Millwright\RadBundle\Form\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\Util\PropertyPath;

use Doctrine\Common\Collections\ArrayCollection;

class ChoicesToValuesTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     *
     * @param ArrayCollection $array array collection of model objects
     *
     * @return array of model objects
     *
     * @throws UnexpectedTypeException if the given value is not an array
     */
    public function transform($array)
    {
        return $array instanceof \Traversable ? iterator_to_array($array) : array();
    }

    /**
     * Do nothing reverse transform
     *
     * @param array $array
     *
     * @return array
     */
    public function reverseTransform($array)
    {
        return $array;
    }
}
